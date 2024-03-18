<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'email',
        'username',
        'password',
        'pegawai_id',
        'role_id'
    ];

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'pegawai_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
