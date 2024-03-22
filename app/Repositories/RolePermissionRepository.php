<?php

namespace App\Repositories;

use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

interface RolePermissionRepositoryInterface
{
    public function save(array $data);
    public function deleteByRoleId(int $roleId);
    public function update($data);
}

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    private $table = 'role_permissions';

    public function save(array $data)
    {
        return DB::table($this->table)->insert($data);

        // return RolePermission::insert($data);
    }

    public function deleteByRoleId(int $roleId)
    {
        return RolePermission::where('role_id', $roleId)->delete();
    }

    public function update($data)
    {
        if (isset($data['read'])) {
            $actions['read'] = $data['read'];
        }
        if (isset($data['create'])) {
            $actions['create'] = $data['create'];
        }
        if (isset($data['update'])) {
            $actions['update'] = $data['update'];
        }
        if (isset($data['delete'])) {
            $actions['delete'] = $data['delete'];
        }

        return DB::table($this->table)
            ->where('role_id', '=', $data['role_id'])
            ->where('permission_id', '=', $data['permission_id'])
            ->update($actions);
    }
}
