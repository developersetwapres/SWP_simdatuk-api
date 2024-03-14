<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = "roles";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'name',
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function rolePermission(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
