<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EchelonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('echelons')->delete();
        $echelons = [
            ['id' => 1, 'name' => 'Eselon I', "retirement_age" => 60],
            ['id' => 2, 'name' => 'Eselon II', "retirement_age" => 60],
            ['id' => 3, 'name' => 'Eselon III', "retirement_age" => 58],
            ['id' => 4, 'name' => 'Eselon IV', "retirement_age" => 58],
            ['id' => 5, 'name' => 'Ahli Utama', "retirement_age" => 65],
            ['id' => 6, 'name' => 'Ahli Madya', "retirement_age" => 60],
            ['id' => 7, 'name' => 'Ahli Muda', "retirement_age" => 58],
            ['id' => 8, 'name' => 'Ahli Pertama', "retirement_age" => 58],
            ['id' => 9, 'name' => 'Pelaksana', "retirement_age" => 58],
            ['id' => 10, 'name' => 'Penyelia', "retirement_age" => 58],
            ['id' => 11, 'name' => 'Mahir', "retirement_age" => 58],
            ['id' => 12, 'name' => 'Terampil', "retirement_age" => 58],
            ['id' => 13, 'name' => 'Pemula', "retirement_age" => 58],
        ];
        DB::table('echelons')->insertTs($echelons);
    }
}
