<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permitted_actions'];

    /**
     * Relationship: Permission belongs to many Roles through role_permissions
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withPivot('create', 'read', 'update', 'delete')
                    ->withTimestamps();
    }
}