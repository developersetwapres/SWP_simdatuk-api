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
        for ($i = 0; $i < 365; $i++) {
            $items = array("Male", "Female");
            // Generate a random number (0 or 1) to select one of the items
            $randomIndex = rand(0, 1);
            // Select the item based on the random index
            $gender = $items[$randomIndex];

            $data = [
                'name' => $faker->name($gender),
                'nip' => $faker->numberBetween($min = 0000000000000, $max = 9999999999999),
                'nrp' => $faker->numberBetween($min = 0000000000, $max = 9999999999),
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $faker->date($format = 'Y-m-d', $min = '1990-01-01', $max = '1970-01-01'),
                'agama' => $faker->numberBetween($min = 1, $max = 6),
                'jenis_kelamin' => ($gender == 'Male') ? 1 : 0,
                'status_perkawinan' => $faker->numberBetween($min = 1, $max = 5),
            ];
            array_push($users, $data);
        }

        DB::table('users')->insertTs($users);
    }
}
