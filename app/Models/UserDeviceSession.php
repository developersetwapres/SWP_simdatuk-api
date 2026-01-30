<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_identifier',
        'device_name',
        'ip_address',
        'user_agent',
        'sanctum_token_id',
        'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    /**
     * Relationship: Device session belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique device identifier based on user agent and IP
     */
    public static function generateDeviceIdentifier($userAgent, $ipAddress)
    {
        return hash('sha256', $userAgent . '|' . $ipAddress . '|' . config('app.key'));
    }

    /**
     * Check if device is already registered for user
     */
    public static function isDeviceRegistered($userId, $deviceIdentifier)
    {
        return self::where('user_id', $userId)
                  ->where('device_identifier', $deviceIdentifier)
                  ->exists();
    }

    /**
     * Clean expired sessions
     */
    public static function cleanExpiredSessions()
    {
        return self::where('last_activity_at', '<', now()->subDays(30))->delete();
    }
}