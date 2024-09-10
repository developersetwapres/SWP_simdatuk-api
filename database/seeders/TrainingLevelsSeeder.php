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
            ['id' => 1, 'level_name' => 'Pelatihan Kepemimpinan Nasional Tingkat I'],
            ['id' => 2, 'level_name' => 'Pelatihan Kepemimpinan Nasional Tingkat II'],
            ['id' => 3, 'level_name' => 'Pelatihan Kepemimpinan Administrator'],
            ['id' => 4, 'level_name' => 'Pelatihan Kepemimpinan Pengawas'],
            ['id' => 5, 'level_name' => 'Pelatihan Dasar CPNS'],
            ['id' => 6, 'level_name' => 'Pelatihan Lainnya'],
            ['id' => 7, 'level_name' => 'Fungsional'],
        ];
        DB::table('training_levels')->insertTs($training_levels);
    }
}
