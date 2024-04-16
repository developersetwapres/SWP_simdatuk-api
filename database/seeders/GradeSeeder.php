<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grades')->delete();
        $grades = [
            ['name' => 'Juru Muda', 'code' => 'I/a', 'type' => 1],
            ['name' => 'Juru Muda Tingkat I', 'code' => 'I/b', 'type' => 1],
            ['name' => 'Juru', 'code' => 'I/c', 'type' => 1],
            ['name' => 'Juru Tingkat I', 'code' => 'I/d', 'type' => 1],
            ['name' => 'Pengatur Muda', 'code' => 'II/a', 'type' => 1],
            ['name' => 'Pengatur Tingkat I', 'code' => 'II/b', 'type' => 1],
            ['name' => 'Penata Muda', 'code' => 'III/a', 'type' => 1],
            ['name' => 'Penata Muda Tingkat I', 'code' => 'III/b', 'type' => 1],
            ['name' => 'Penata', 'code' => 'III/c', 'type' => 1],
            ['name' => 'Penata Tingkat I', 'code' => 'III/d', 'type' => 1],
            ['name' => 'Pembina', 'code' => 'IV/a', 'type' => 1],
            ['name' => 'Pembina Tingkat I', 'code' => 'IV/b', 'type' => 1],
            ['name' => 'Pembina Utama Muda', 'code' => 'IV/c', 'type' => 1],
            ['name' => 'Pembina Utama Madya', 'code' => 'IV/d', 'type' => 1],
            ['name' => 'Pembina Utama', 'code' => 'IV/e', 'type' => 1],
            ['name' => 'Golongan I', 'code' => 'I', 'type' => 2],
            ['name' => 'Golongan II', 'code' => 'II', 'type' => 2],
            ['name' => 'Golongan III', 'code' => 'III', 'type' => 2],
            ['name' => 'Golongan IV', 'code' => 'IV', 'type' => 2],
            ['name' => 'Golongan V', 'code' => 'V', 'type' => 2],
            ['name' => 'Golongan VI', 'code' => 'VI', 'type' => 2],
            ['name' => 'Golongan VII', 'code' => 'VII', 'type' => 2],
            ['name' => 'Golongan VIII', 'code' => 'VIII', 'type' => 2],
            ['name' => 'Golongan IX', 'code' => 'IX', 'type' => 2],
            ['name' => 'Golongan X', 'code' => 'X', 'type' => 2],
            ['name' => 'Golongan XI', 'code' => 'XI', 'type' => 2],
            ['name' => 'Golongan XII', 'code' => 'XII', 'type' => 2],
            ['name' => 'Golongan XIII', 'code' => 'XIII', 'type' => 2],
            ['name' => 'Golongan XIV', 'code' => 'XIV', 'type' => 2],
            ['name' => 'Golongan XV', 'code' => 'XV', 'type' => 2],
            ['name' => 'Golongan XVI', 'code' => 'XVI', 'type' => 2],
            ['name' => 'Golongan XVII', 'code' => 'XVII', 'type' => 2],
        ];
        DB::table('grades')->insertTs($grades);
    }
}
