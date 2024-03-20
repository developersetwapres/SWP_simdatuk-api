<?php

namespace App\Repositories;

use App\Models\RolePermission;

interface RolePermissionRepositoryInterface
{
    public function save(array $data);
}

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function save(array $data)
    {
        return RolePermission::insert($data);
    }
}
