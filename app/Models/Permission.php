<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    protected $table = "permissions";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = "true";

    protected $fillable = [
        'name',
        'group',
        'permitted_actions',
    ];

    public function rolePermission(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }
}
