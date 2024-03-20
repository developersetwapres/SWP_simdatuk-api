<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        DB::table('users')->delete();
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin' . config('mail.domain'),
                'password' => Hash::make('admin'),
            ],
        ];
        DB::table('users')->insert($users, false);
    }
}
