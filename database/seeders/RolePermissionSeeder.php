<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 28; $i++) {
            $d = [
                'role_id' => 1,
                'permission_id' => $i,
                'read' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ];

            array_push($data, $d);
        }

        DB::table('role_permissions')->insert($data);
    }
}
