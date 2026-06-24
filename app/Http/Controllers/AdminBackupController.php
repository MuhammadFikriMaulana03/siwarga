<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBackupController extends Controller
{
    private function ensureAdminRw(): void
    {
        $user = User::findOrFail(Auth::id());

        abort_if($user->role !== 'admin_rw', 403);
    }

    public function index()
    {
        $this->ensureAdminRw();

        return view('admin.backups.index');
    }

    public function download()
    {
        $this->ensureAdminRw();

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $fileName = 'backup-siwarga-' . now()->format('Y-m-d-His') . '.sql';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';

        if (!file_exists($mysqldump)) {
            return back()->with('error', 'File mysqldump.exe tidak ditemukan. Pastikan XAMPP terinstall di C:\xampp.');
        }

        $command = "\"{$mysqldump}\" --user={$username} --host={$host}";

        if (!empty($password)) {
            $command .= " --password={$password}";
        }

        $command .= " {$database} > \"{$filePath}\" 2>&1";

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($filePath)) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return back()->with('error', 'Backup database gagal dibuat: ' . implode(' ', $output));
        }

        ActivityLog::record(
            'Backup Data',
            'Download Backup',
            'Admin mengunduh backup database: ' . $fileName
        );

        return response()
            ->download($filePath, $fileName)
            ->deleteFileAfterSend(true);
    }

    public function restore(Request $request)
    {
        $this->ensureAdminRw();

        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:sql,txt', 'max:51200'],
        ]);

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $mysql = 'C:\xampp\mysql\bin\mysql.exe';

        if (!file_exists($mysql)) {
            return back()->with('error', 'File mysql.exe tidak ditemukan. Pastikan XAMPP terinstall di C:\xampp.');
        }

        $restoreDir = storage_path('app/restore-temp');

        if (!is_dir($restoreDir)) {
            mkdir($restoreDir, 0755, true);
        }

        $fileName = 'restore-' . now()->format('Y-m-d-His') . '.sql';
        $fullPath = $restoreDir . DIRECTORY_SEPARATOR . $fileName;

        $request->file('backup_file')->move($restoreDir, $fileName);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'File backup gagal diupload ke server.');
        }

        $command = 'cmd /c ""' . $mysql . '"'
            . ' --user=' . escapeshellarg($username)
            . ' --host=' . escapeshellarg($host);

        if (!empty($password)) {
            $command .= ' --password=' . escapeshellarg($password);
        }

        $command .= ' ' . escapeshellarg($database)
            . ' < "' . $fullPath . '" 2>&1"';

        exec($command, $output, $resultCode);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        if ($resultCode !== 0) {
            return back()->with('error', 'Restore database gagal: ' . implode(' ', $output));
        }

        ActivityLog::record(
            'Backup Data',
            'Restore Database',
            'Admin melakukan restore database dari file SQL.'
        );

        return redirect()
            ->route('admin.backups.index')
            ->with('success', 'Restore database berhasil dilakukan.');
    }
}
