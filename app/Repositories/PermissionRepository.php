<?php

namespace App\Repositories;

use App\Models\Permission;

interface PermissionRepositoryInterface
{
    public function list();
}

class PermissionRepository implements PermissionRepositoryInterface
{
    public function list()
    {
        $permission = Permission::get([
            'id',
            'group',
            'name',
            'permitted_actions'
        ]);

        return $permission;
    }
}