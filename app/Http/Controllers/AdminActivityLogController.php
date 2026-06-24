<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $module = $request->get('module');

        $logs = ActivityLog::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($module !== null && $module !== '', function ($query) use ($module) {
                $query->where('module', $module);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $modules = ActivityLog::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('admin.activity-logs.index', compact(
            'logs',
            'modules',
            'search',
            'module'
        ));
    }

    public function export(Request $request)
{
    $search = trim($request->get('search', ''));
    $module = $request->get('module');

    $logs = ActivityLog::with('user')
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        })
        ->when($module !== null && $module !== '', function ($query) use ($module) {
            $query->where('module', $module);
        })
        ->latest()
        ->get();

    $fileName = 'log-aktivitas-' . now()->format('Y-m-d-His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
    ];

    $callback = function () use ($logs) {
        $file = fopen('php://output', 'w');

        // BOM supaya Excel Indonesia aman
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($file, [
            'Waktu',
            'User',
            'Email',
            'Role',
            'Modul',
            'Aksi',
            'Keterangan',
            'IP Address',
        ], ';');

        foreach ($logs as $log) {
            fputcsv($file, [
                $log->created_at?->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                $log->user->name ?? 'System',
                $log->user->email ?? '-',
                $log->role ?? '-',
                $log->module,
                $log->action,
                $log->description ?? '-',
                $log->ip_address ?? '-',
            ], ';');
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function exportPdf(Request $request)
{
    $search = trim($request->get('search', ''));
    $module = $request->get('module');

    $logs = ActivityLog::with('user')
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        })
        ->when($module !== null && $module !== '', function ($query) use ($module) {
            $query->where('module', $module);
        })
        ->latest()
        ->get();

    $pdf = Pdf::loadView('admin.activity-logs.pdf', compact(
        'logs',
        'search',
        'module'
    ))->setPaper('a4', 'landscape');

    return $pdf->stream('log-aktivitas-' . now()->format('Y-m-d-His') . '.pdf');
}
}
