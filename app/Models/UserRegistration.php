<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegistration extends Model
{
    protected $table = "user_registrations";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'role_id',
        'pegawai_id',
        'email',
        'username',
        'is_verified',
        'verification_key',
        'expired_at',
    ];
}
