<?php

namespace App\Repositories;

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
        return DB::table($this->table)->where('name', $name)->first();
    }

    public function list()
    {
        return DB::table($this->table)->get([
            'id',
            'name',
            'created_at',
            'updated_at'
        ]);
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
    }

    public function save(string $name)
    {
        return DB::table($this->table)->insertGetId([
            'name' => $name
        ]);
    }

    public function update(int $roleId, string $name)
    {
        return DB::table($this->table)->where('id', $roleId)
            ->update([
                'name' => $name
            ]);
    }

    public function findById(int $roleId)
    {
        return DB::table($this->table)->find($roleId);
    }

    public function findByMultipleId(array $ids)
    {
        return DB::table($this->table)->whereIn('id', $ids)->get();
    }

    public function delete(int $roleId)
    {
        return DB::table($this->table)->where('id', $roleId)->delete();
    }
}
