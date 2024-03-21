<?php

namespace App\Repositories;

use App\Models\Permission;

interface PermissionRepositoryInterface
{
    public function list();
    public function listGroup();
}

class PermissionRepository implements PermissionRepositoryInterface
{
    public function list(mixed $filter = null, bool $groupBy = false)
    {
        return Permission::get([
            'id',
            'group',
            'name',
            'permitted_actions'
        ]);
    }

    public function listGroup()
    {
        return Permission::get()
            ->groupBy('group');
    }
}
