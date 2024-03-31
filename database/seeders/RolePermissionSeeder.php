<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_permissions')->delete();
        $role = DB::table('roles')->select('id')->where('name', 'administrator')->first();
        $permissions = DB::table('permissions')->get();
        $dataInsert = [];
        foreach ($permissions as $permission) {
            $data = [
                'role_id' => $role->id,
                'permission_id' => $permission->id,
                'create' => (str_contains($permission->permitted_actions, 'c')) ? true : false,
                'read' => (str_contains($permission->permitted_actions, 'r')) ? true : false,
                'update' => (str_contains($permission->permitted_actions, 'u')) ? true : false,
                'delete' => (str_contains($permission->permitted_actions, 'd')) ? true : false,
            ];
            array_push($dataInsert, $data);
        }
        DB::table('role_permissions')->insertTs($dataInsert);
    }
}
