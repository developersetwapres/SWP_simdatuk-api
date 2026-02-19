<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email', 
        'username',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship: User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: User has many device sessions
     */
    public function deviceSessions()
    {
        return $this->hasMany(UserDeviceSession::class);
    }

    /**
     * Revoke all tokens and device sessions except current
     */
    public function revokeOtherDeviceSessions($currentTokenId = null)
    {
        // Revoke all Sanctum tokens except current
        $tokensQuery = $this->tokens();
        if ($currentTokenId) {
            $tokensQuery->where('id', '!=', $currentTokenId);
        }
        $tokensQuery->delete();

        // Remove device sessions except current
        $sessionsQuery = $this->deviceSessions();
        if ($currentTokenId) {
            $sessionsQuery->where('sanctum_token_id', '!=', $currentTokenId);
        }
        $sessionsQuery->delete();

        return true;
    }

    /**
     * Get user permissions through role
     */
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'id', // Foreign key on roles table
            'id', // Foreign key on permissions table  
            'role_id', // Local key on users table
            'id' // Local key on roles table
        )->through('role_permissions');
    }

    /**
     * Check if user has specific permission with action
     */
    public function hasPermission(string $permissionName, string $action = 'read'): bool
    {
        if (!$this->role_id) {
            return false;
        }

        return DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $this->role_id)
            ->where('p.name', $permissionName)
            ->where("rp.{$action}", true)
            ->exists();
    }

    /**
     * Get all user permissions with CRUD flags
     */
    public function getAllPermissions()
    {
        if (!$this->role_id) {
            return collect();
        }

        return DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $this->role_id)
            ->select('p.id', 'p.name', 'rp.create', 'rp.read', 'rp.update', 'rp.delete')
            ->get();
    }

    /**
     * Generate Token
     *
     * @return void
     */
    public function generateToken()
    {
        $code = Str::random(40);
        $verificationCode = DB::table('password_reset_tokens')->where('verification_code', '=', $code)->first();

        if (!$verificationCode) {
            return $code;
        } else {
            return $this->generateToken();
        }
    }

    /**
     * Generate OTP
     */
    public function generateOtp()
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $verificationCode = DB::table('otps')->where('code', '=', $code)->first();

        if (!$verificationCode) {
            return $code;
        } else {
            return $this->generateOtp();
        }
    }
}
