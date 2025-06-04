<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_types')->delete();
        $employmentTypes = [
            ['id' => 1, 'name' => 'TNI/POLRI', 'type' => 1, 'sequence_number' => 3, 'status' => true],
            ['id' => 2, 'name' => 'SIPIL', 'type' => 1, 'sequence_number' => 4, 'status' => true],
            ['id' => 3, 'name' => 'ORGANIK', 'type' => 1, 'sequence_number' => 1, 'status' => true],
            ['id' => 4, 'name' => 'PPPK', 'type' => 1, 'sequence_number' => 2, 'status' => true],
            ['id' => 5, 'name' => 'STAF KHUSUS', 'type' => 2, 'sequence_number' => 1, 'status' => true],
            ['id' => 6, 'name' => 'ASISTEN / STAF', 'type' => 2, 'sequence_number' => 2, 'status' => true],
            ['id' => 7, 'name' => 'ASISTEN STAF KHUSUS', 'type' => 2, 'sequence_number' => 3, 'status' => true],
            ['id' => 8, 'name' => 'SEKRETARIAT PADA STAF KHUSUS', 'type' => 2, 'sequence_number' => 4, 'status' => true],
            ['id' => 9, 'name' => 'ANGGOTA TIM AHLI', 'type' => 2, 'sequence_number' => 5, 'status' => true],
            ['id' => 10, 'name' => 'TNI/POLRI (TIM AJUDAN)', 'type' => 2, 'sequence_number' => 6, 'status' => true],
            ['id' => 11, 'name' => 'TNI/POLRI (TIM DOKTER PRIBADI)', 'type' => 2, 'sequence_number' => 7, 'status' => true],
            ['id' => 12, 'name' => 'TNI/POLRI (PENGEMUDI)', 'type' => 2, 'sequence_number' => 8, 'status' => true],
            ['id' => 13, 'name' => 'PEMBANTU ASISTEN STAF KHUSUS', 'type' => 2, 'sequence_number' => 9, 'status' => true],
            ['id' => 14, 'name' => 'STAF PADA SESPRI', 'type' => 2, 'sequence_number' => 10, 'status' => true],
            ['id' => 15, 'name' => 'TPPS', 'type' => 2, 'sequence_number' => 11, 'status' => true],
            ['id' => 16, 'name' => 'TNP2K', 'type' => 2, 'sequence_number' => 12, 'status' => true],
            ['id' => 17, 'name' => 'TNI/POLRI (PROTOKOL)', 'type' => 2, 'sequence_number' => 13, 'status' => true],
            ['id' => 18, 'name' => 'SESPRI', 'type' => 2, 'sequence_number' => 14, 'status' => true],
            ['id' => 19, 'name' => 'OUTSOURCE', 'type' => 3, 'sequence_number' => 15, 'status' => true],
            ['id' => 20, 'name' => 'REKANAN', 'type' => 3, 'sequence_number' => 16, 'status' => true],
        ];
        DB::table('employment_types')->insertTs($employmentTypes);
    }
}
