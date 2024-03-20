<?php

namespace App\Repositories;

use App\Models\RolePermission;

interface RolePermissionRepositoryInterface
{
    public function save(array $data);
    public function updatePermissionAction(mixed $rolePermission, array $action);
    public function findByRoleAndPermissionId(int $roleId, int $permissionId);
}

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function save(array $data)
    {
        return RolePermission::insert($data);
    }

    public function updatePermissionAction(mixed $rolePermission, array $action)
    {
        if (isset($action['read']))
        {
            $rolePermission->read = $action['read'];
        }
        if (isset($action['create']))
        {
            $rolePermission->create = $action['create'];
        }
        if (isset($action['update']))
        {
            $rolePermission->update = $action['update'];
        }
        if (isset($action['delete']))
        {
            $rolePermission->delete = $action['delete'];
        }

        $rolePermission->save();

        return $rolePermission;
    }

    public function findByRoleAndPermissionId(int $roleId, int $permissionId)
    {
        return RolePermission::where('permission_id', $permissionId)->where('role_id', $roleId)->first();
    }
}
