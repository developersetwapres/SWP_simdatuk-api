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
            ['id' => 1, 'name' => 'Eselon I', 'sort' => 1, "retirement_age" => 60],
            ['id' => 2, 'name' => 'Eselon II', 'sort' => 2, "retirement_age" => 60],
            ['id' => 3, 'name' => 'Eselon III', 'sort' => 3, "retirement_age" => 58],
            ['id' => 4, 'name' => 'Eselon IV', 'sort' => 4, "retirement_age" => 58],
            ['id' => 5, 'name' => 'Ahli Utama', 'sort' => 5, "retirement_age" => 65],
            ['id' => 6, 'name' => 'Ahli Madya', 'sort' => 6, "retirement_age" => 60],
            ['id' => 7, 'name' => 'Ahli Muda', 'sort' => 7, "retirement_age" => 58],
            ['id' => 8, 'name' => 'Ahli Pertama', 'sort' => 8, "retirement_age" => 58],
            ['id' => 9, 'name' => 'Pelaksana', 'sort' => 13, "retirement_age" => 58],
            ['id' => 10, 'name' => 'Penyelia', 'sort' => 9, "retirement_age" => 58],
            ['id' => 11, 'name' => 'Mahir', 'sort' => 10, "retirement_age" => 58],
            ['id' => 12, 'name' => 'Terampil', 'sort' => 11, "retirement_age" => 58],
            ['id' => 13, 'name' => 'Pemula', 'sort' => 12, "retirement_age" => 58],
        ];
        DB::table('echelons')->insertTs($echelons);
    }
}
