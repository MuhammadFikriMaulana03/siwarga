<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MyProfileController extends Controller
{
    public function edit()
    {
        $user = User::findOrFail(Auth::id());

        return view('profile-saya.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                'Profil',
                'Update Profil',
                'User memperbarui profil sendiri: ' . $user->name
            );
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function removePhoto()
    {
        $user = User::findOrFail(Auth::id());

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->profile_photo = null;
        $user->save();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                'Profil',
                'Hapus Foto Profil',
                'User menghapus foto profil sendiri: ' . $user->name
            );
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}