<?php

namespace Database\Seeders;

use App\Helpers\Document;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use Document;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') != 'production') {
            DB::table('users')->delete();
            $faker = Faker::create('id_ID');

            // Added administrator
            $role = DB::table('roles')->select('id')->where('name', 'administrator')->first();
            $admin = [
                [
                    'role_id' => $role->id,
                    'email' => 'admin' . config('mail.domain'),
                    'username' => 'admin',
                    'password' => Hash::make('password'), // default password
                    'name' => 'administrator',
                    'employee_id_number' => '0000000000000',
                    'employee_registration_number' => '0000000000000',
                    'status' => true,
                ],
            ];
            DB::table('users')->insertTs($admin);

            // Sample user
            $users = [];
            for ($i = 0; $i < 100; $i++) {
                $items = array("Male", "Female");
                // Generate a random number (0 or 1) to select one of the items
                $randomIndex = rand(0, 1);
                // Select the item based on the random index
                $gender = $items[$randomIndex];

                $data = [
                    'name' => $faker->name($gender),
                    'employee_id_number' => $faker->numberBetween($min = 0000000000000, $max = 9999999999999),
                    'employee_registration_number' => $faker->numberBetween($min = 0000000000, $max = 9999999999),
                    'place_of_birth' => $faker->city(),
                    'date_of_birth' => $faker->date($format = 'Y-m-d', $min = '1990-01-01', $max = '1970-01-01'),
                    'religion' => $faker->numberBetween($min = 1, $max = 6),
                    'gender' => ($gender == 'Male') ? 1 : 0,
                    'marital_status' => $faker->numberBetween($min = 1, $max = 5),
                    'type' => mt_rand(1, 3),
                ];
                array_push($users, $data);
            }

            DB::table('users')->insertTs($users);
        }
    }
}
