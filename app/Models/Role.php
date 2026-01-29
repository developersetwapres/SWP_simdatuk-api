<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: Role has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship: Role has many Permissions through role_permissions
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withPivot('create', 'read', 'update', 'delete')
                    ->withTimestamps();
    }
}