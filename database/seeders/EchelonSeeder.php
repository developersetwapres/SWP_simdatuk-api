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
            ['id' => 1, 'name' => 'Eselon I', 'sequence_number' => 1, "retirement_age" => 60],
            ['id' => 2, 'name' => 'Eselon II', 'sequence_number' => 2, "retirement_age" => 60],
            ['id' => 3, 'name' => 'Eselon III', 'sequence_number' => 3, "retirement_age" => 58],
            ['id' => 4, 'name' => 'Eselon IV', 'sequence_number' => 4, "retirement_age" => 58],
            ['id' => 5, 'name' => 'Ahli Utama', 'sequence_number' => 5, "retirement_age" => 65],
            ['id' => 6, 'name' => 'Ahli Madya', 'sequence_number' => 6, "retirement_age" => 60],
            ['id' => 7, 'name' => 'Ahli Muda', 'sequence_number' => 7, "retirement_age" => 58],
            ['id' => 8, 'name' => 'Ahli Pertama', 'sequence_number' => 8, "retirement_age" => 58],
            ['id' => 9, 'name' => 'Pelaksana', 'sequence_number' => 13, "retirement_age" => 58],
            ['id' => 10, 'name' => 'Penyelia', 'sequence_number' => 9, "retirement_age" => 58],
            ['id' => 11, 'name' => 'Mahir', 'sequence_number' => 10, "retirement_age" => 58],
            ['id' => 12, 'name' => 'Terampil', 'sequence_number' => 11, "retirement_age" => 58],
            ['id' => 13, 'name' => 'Pemula', 'sequence_number' => 12, "retirement_age" => 58],
        ];
        DB::table('echelons')->insertTs($echelons);
    }
}
