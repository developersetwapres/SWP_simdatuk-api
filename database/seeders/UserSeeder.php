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
            'email' => 'super@admin.com',
            'role_id' => 1,
            'pegawai_id' => 1,
            'status' => true
        ]);
    }
}
