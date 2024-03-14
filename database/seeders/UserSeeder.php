<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'super.admin',
            'password'=> Hash::make('password123'),
            'name' => 'super admin',
            'email' => 'super@admin.com',
            'role_id' => 1,
            'nip' => 'super0001',
            'nrp' => 'super1000',
            'status' => true
        ]);
    }
}
