<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\User;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $role = $request->get('role');

        $users = User::with(['rt', 'warga'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('warga', function ($wargaQuery) use ($search) {
                            $wargaQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                        });
                });
            })
            ->when($role !== null && $role !== '', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function create()
    {
        $rts = Rt::orderBy('nomor_rt')->get();
        $wargas = Warga::with('rt')->orderBy('nama')->get();

        return view('admin.users.create', compact('rts', 'wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin_rw,ketua_rt,warga'],
            'rt_id' => ['nullable', 'exists:rts,id'],
            'warga_id' => ['nullable', 'exists:wargas,id'],
        ]);

        if ($validated['role'] === 'ketua_rt' && empty($validated['rt_id'])) {
            return back()
                ->withInput()
                ->with('error', 'Role Ketua RT wajib memilih RT.');
        }

        if ($validated['role'] === 'warga' && empty($validated['warga_id'])) {
            return back()
                ->withInput()
                ->with('error', 'Role Warga wajib memilih data warga.');
        }

        if ($validated['role'] !== 'ketua_rt') {
            $validated['rt_id'] = null;
        }

        if ($validated['role'] !== 'warga') {
            $validated['warga_id'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        ActivityLog::record(
            'Kelola User',
            'Tambah User',
            'Membuat akun user: ' . $user->name . ' (' . $user->role . ')'
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun user berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $rts = Rt::orderBy('nomor_rt')->get();
        $wargas = Warga::with('rt')->orderBy('nama')->get();

        return view('admin.users.edit', compact('user', 'rts', 'wargas'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin_rw,ketua_rt,warga'],
            'rt_id' => ['nullable', 'exists:rts,id'],
            'warga_id' => ['nullable', 'exists:wargas,id'],
        ]);

        if ($validated['role'] === 'ketua_rt' && empty($validated['rt_id'])) {
            return back()
                ->withInput()
                ->with('error', 'Role Ketua RT wajib memilih RT.');
        }

        if ($validated['role'] === 'warga' && empty($validated['warga_id'])) {
            return back()
                ->withInput()
                ->with('error', 'Role Warga wajib memilih data warga.');
        }

        if ($validated['role'] !== 'ketua_rt') {
            $validated['rt_id'] = null;
        }

        if ($validated['role'] !== 'warga') {
            $validated['warga_id'] = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::record(
            'Kelola User',
            'Edit User',
            'Mengubah akun user: ' . $user->name . ' (' . $user->role . ')'
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Akun yang sedang login tidak bisa dihapus.');
        }

        ActivityLog::record(
            'Kelola User',
            'Hapus User',
            'Menghapus akun user: ' . $user->name . ' (' . $user->role . ')'
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }
}
