<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'module',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $module, string $action, ?string $description = null): void
    {
        $user = Auth::user();

        self::create([
            'user_id' => $user?->id,
            'role' => $user?->role,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
