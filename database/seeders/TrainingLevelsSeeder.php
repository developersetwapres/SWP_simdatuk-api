<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('training_levels')->delete();
        $training_levels = [
            // Jenjang Struktural
            ['id' => 1, 'level_name' => 'Pelatihan Kepemimpinan Nasional Tingkat I', 'level_type'=> 1],
            ['id' => 2, 'level_name' => 'Pelatihan Kepemimpinan Nasional Tingkat II', 'level_type'=> 1],
            ['id' => 3, 'level_name' => 'Pelatihan Kepemimpinan Administrator', 'level_type'=> 1],
            ['id' => 4, 'level_name' => 'Pelatihan Kepemimpinan Pengawas', 'level_type'=> 1],
            ['id' => 5, 'level_name' => 'Pelatihan Dasar CPNS', 'level_type'=> 1],
            ['id' => 6, 'level_name' => 'Pelatihan Lainnya', 'level_type'=> 1],

            // Jenjang Fungsional
            ['id' => 7,  'level_name' => 'Fungsional', 'level_type'=> 2],
            ['id' => 8,  'level_name' => 'Terampil', 'level_type'=> 2],
            ['id' => 9,  'level_name' => 'Mahir', 'level_type'=> 2],
            ['id' => 10, 'level_name' => 'Penyelia', 'level_type'=> 2],
            ['id' => 11, 'level_name' => 'Ahli Pertama', 'level_type'=> 2],
            ['id' => 12, 'level_name' => 'Ahli Muda', 'level_type'=> 2],
            ['id' => 13, 'level_name' => 'Ahli Madya', 'level_type'=> 2],
            ['id' => 14, 'level_name' => 'Ahli Utama', 'level_type'=> 2],
        ];
        DB::table('training_levels')->insertTs($training_levels);
    }
}
