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

        // Added administrator
        $role = DB::table('roles')->select('id')->where('name', 'administrator')->first();
        $admin = [
            [
                'role_id' => $role->id,
                'email' => 'admin' . config('mail.domain'),
                'username' => 'admin',
                'password' => Hash::make('password'), // default password
                'name' => 'administrator',
                'nip' => '0000000000000',
                'nrp' => '0000000000000',
                'status' => true,
            ],
        ];
        DB::table('users')->insertTs($admin);

        // Sample user
        $users = [];
        for ($i = 0; $i < 30; $i++) {
            $name = $faker->name();
            $data = [
                'name' => $name,
            ];
            array_push($users, $data);
        }

        DB::table('users')->insertTs($users);
    }
}
