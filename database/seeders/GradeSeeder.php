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
            ['name' => 'Pembina Utama', 'code' => 'IV/e', 'type' => 1],
            ['name' => 'Pembina Utama Madya', 'code' => 'IV/d', 'type' => 1],
            ['name' => 'Pembina Utama Muda', 'code' => 'IV/c', 'type' => 1],
            ['name' => 'Pembina Tingkat I', 'code' => 'IV/b', 'type' => 1],
            ['name' => 'Pembina', 'code' => 'IV/a', 'type' => 1],
            ['name' => 'Penata Tingkat I', 'code' => 'III/d', 'type' => 1],
            ['name' => 'Penata', 'code' => 'III/c', 'type' => 1],
            ['name' => 'Penata Muda Tingkat I', 'code' => 'III/b', 'type' => 1],
            ['name' => 'Penata Muda', 'code' => 'III/a', 'type' => 1],
            ['name' => 'Pengatur Tingkat I', 'code' => 'II/b', 'type' => 1],
            ['name' => 'Pengatur Muda', 'code' => 'II/a', 'type' => 1],
            ['name' => 'Juru Tingkat I', 'code' => 'I/d', 'type' => 1],
            ['name' => 'Juru', 'code' => 'I/c', 'type' => 1],
            ['name' => 'Juru Muda Tingkat I', 'code' => 'I/b', 'type' => 1],
            ['name' => 'Juru Muda', 'code' => 'I/a', 'type' => 1],
            ['name' => '-', 'code' => 'I', 'type' => 2],
            ['name' => '-', 'code' => 'II', 'type' => 2],
            ['name' => '-', 'code' => 'III', 'type' => 2],
            ['name' => '-', 'code' => 'IV', 'type' => 2],
            ['name' => '-', 'code' => 'V', 'type' => 2],
            ['name' => '-', 'code' => 'VI', 'type' => 2],
            ['name' => '-', 'code' => 'VII', 'type' => 2],
            ['name' => '-', 'code' => 'VIII', 'type' => 2],
            ['name' => '-', 'code' => 'IX', 'type' => 2],
            ['name' => '-', 'code' => 'X', 'type' => 2],
            ['name' => '-', 'code' => 'XI', 'type' => 2],
            ['name' => '-', 'code' => 'XII', 'type' => 2],
            ['name' => '-', 'code' => 'XIII', 'type' => 2],
            ['name' => '-', 'code' => 'XIV', 'type' => 2],
            ['name' => '-', 'code' => 'XV', 'type' => 2],
            ['name' => '-', 'code' => 'XVI', 'type' => 2],
            ['name' => '-', 'code' => 'XVII', 'type' => 2],
        ];
        DB::table('grades')->insertTs($grades);
    }
}
