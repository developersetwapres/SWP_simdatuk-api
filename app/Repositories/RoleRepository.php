<?php

namespace App\Repositories;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function findByName(string $name);
    public function list();
    public function roleDetail(int $roleId);
    public function save(string $data);
}

class RoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name)
    {
        return Role::where('name', $name)->first();
    }

    public function list()
    {
        return Role::get([
            'id',
            'name'
        ]);
    }

    public function roleDetail(int $roleId)
    {
        return Role::where('roles.id', $roleId)
            ->join('role_permissions', 'role_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->get([
                'roles.id AS role_id',
                'roles.name AS role_name',

                'permissions.id AS permission_id',
                'permissions.group AS permission_group',
                'permissions.name AS permission_name',

                'role_permissions.read AS action_read',
                'role_permissions.create AS action_create',
                'role_permissions.update AS action_update',
                'role_permissions.delete AS action_delete',
            ]);
    }

    public function save(string $name)
    {
        $role = Role::create(['name' => $name]);

        return $role->id;
    }
}
