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
            ['id' => 1, 'name' => 'Pembina Utama', 'code' => '(IV/e)', 'type' => 1],
            ['id' => 2, 'name' => 'Pembina Utama Madya', 'code' => '(IV/d)', 'type' => 1],
            ['id' => 3, 'name' => 'Pembina Utama Muda', 'code' => '(IV/c)', 'type' => 1],
            ['id' => 4, 'name' => 'Pembina Tingkat I', 'code' => '(IV/b)', 'type' => 1],
            ['id' => 5, 'name' => 'Pembina', 'code' => '(IV/a)', 'type' => 1],
            ['id' => 6, 'name' => 'Penata Tingkat I', 'code' => '(III/d)', 'type' => 1],
            ['id' => 7, 'name' => 'Penata', 'code' => '(III/c)', 'type' => 1],
            ['id' => 8, 'name' => 'Penata Muda Tingkat I', 'code' => '(III/b)', 'type' => 1],
            ['id' => 9, 'name' => 'Penata Muda', 'code' => '(III/a)', 'type' => 1],
            ['id' => 10, 'name' => 'Pengatur Tingkat I', 'code' => '(II/d)', 'type' => 1],
            ['id' => 11, 'name' => 'Pengatur', 'code' => '(II/c)', 'type' => 1],
            ['id' => 12, 'name' => 'Pengatur Muda Tingkat I', 'code' => '(II/b)', 'type' => 1],
            ['id' => 13, 'name' => 'Pengatur Muda', 'code' => '(II/a)', 'type' => 1],
            ['id' => 14, 'name' => 'Juru Tingkat I', 'code' => '(I/d)', 'type' => 1],
            ['id' => 15, 'name' => 'Juru', 'code' => '(I/c)', 'type' => 1],
            ['id' => 16, 'name' => 'Juru Muda Tingkat I', 'code' => '(I/b)', 'type' => 1],
            ['id' => 17, 'name' => 'Juru Muda', 'code' => '(I/a)', 'type' => 1],
            ['id' => 18, 'name' => 'Golongan XVII', 'code' => '(XVII)', 'type' => 2],
            ['id' => 19, 'name' => 'Golongan XVI', 'code' => '(XVI)', 'type' => 2],
            ['id' => 20, 'name' => 'Golongan XV', 'code' => '(XV)', 'type' => 2],
            ['id' => 21, 'name' => 'Golongan XIV', 'code' => '(XIV)', 'type' => 2],
            ['id' => 22, 'name' => 'Golongan XIII', 'code' => '(XIII)', 'type' => 2],
            ['id' => 23, 'name' => 'Golongan XII', 'code' => '(XII)', 'type' => 2],
            ['id' => 24, 'name' => 'Golongan XI', 'code' => '(XI)', 'type' => 2],
            ['id' => 25, 'name' => 'Golongan X', 'code' => '(X)', 'type' => 2],
            ['id' => 26, 'name' => 'Golongan IX', 'code' => '(IX)', 'type' => 2],
            ['id' => 27, 'name' => 'Golongan VIII', 'code' => '(VIII)', 'type' => 2],
            ['id' => 28, 'name' => 'Golongan VII', 'code' => '(VII)', 'type' => 2],
            ['id' => 29, 'name' => 'Golongan VI', 'code' => '(VI)', 'type' => 2],
            ['id' => 30, 'name' => 'Golongan V', 'code' => '(V)', 'type' => 2],
            ['id' => 31, 'name' => 'Golongan IV', 'code' => '(IV)', 'type' => 2],
            ['id' => 32, 'name' => 'Golongan III', 'code' => '(III)', 'type' => 2],
            ['id' => 33, 'name' => 'Golongan II', 'code' => '(II)', 'type' => 2],
            ['id' => 34, 'name' => 'Golongan I', 'code' => '(I)', 'type' => 2],
        ];
        DB::table('grades')->insertTs($grades);
    }
}
