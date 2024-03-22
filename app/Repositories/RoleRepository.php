<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

interface RoleRepositoryInterface
{
    public function findByName(string $name);
    public function list();
    public function roleDetail(int $roleId);
    public function save(string $data);
    public function update(int $roleId, string $name);
    public function findById(int $roleId);
    public function findByMultipleId(array $ids);
    public function delete(int $roleId);
}

class RoleRepository implements RoleRepositoryInterface
{
    private $table = 'roles';

    public function findByName(string $name)
    {
        return Role::where('name', $name)->first();
    }

    public function list()
    {
        return DB::table($this->table)->get([
            'id',
            'name',
            'created_at',
            'updated_at'
        ]);

        // return Role::get([
        //     'id',
        //     'name'
        // ]);
    }

    public function roleDetail(int $roleId)
    {
        return DB::table($this->table)
            ->where('roles.id', $roleId)
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

        // return Role::where('roles.id', $roleId)
        //     ->join('role_permissions', 'role_permissions.role_id', '=', 'roles.id')
        //     ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
        //     ->get([
        //         'roles.id AS role_id',
        //         'roles.name AS role_name',

        //         'permissions.id AS permission_id',
        //         'permissions.group AS permission_group',
        //         'permissions.name AS permission_name',

        //         'role_permissions.read AS action_read',
        //         'role_permissions.create AS action_create',
        //         'role_permissions.update AS action_update',
        //         'role_permissions.delete AS action_delete',
        //     ]);
    }

    public function save(string $name)
    {
        return DB::table($this->table)->insertGetId([
            'name' => $name
        ]);

        // $role = Role::create(['name' => $name]);

        // return $role->id;
    }

    public function update(int $roleId, string $name)
    {
        return DB::table($this->table)->where('id', $roleId)
            ->update([
                'name' => $name
            ]);

        // $role = Role::findOrFail($roleId);
        
        // $role->name = $name;

        // $role->save();

        // return $role;
    }

    public function findById(int $roleId)
    {
        return DB::table($this->table)->find($roleId);
        
        // return Role::findOrFail($roleId);
    }

    public function findByMultipleId(array $ids)
    {
        return Role::find($ids);
    }

    public function delete(int $roleId)
    {
        return Role::where('id', $roleId)->delete();
    }
}
