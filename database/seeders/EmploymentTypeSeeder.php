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
            ['name' => 'TNI/POLRI', 'status' => true, 'type' => 1],
            ['name' => 'SIPIL', 'status' => true, 'type' => 1],
            ['name' => 'ORGANIK', 'status' => true, 'type' => 1],
            ['name' => 'PPPK', 'status' => true, 'type' => 1],
            ['name' => 'UTSOURCE', 'status' => true, 'type' => 2],
            ['name' => 'REKANAN', 'status' => true, 'type' => 2],
            ['name' => 'STAF KHUSUS', 'status' => true, 'type' => 3],
            ['name' => 'ASISTEN / STAF', 'status' => true, 'type' => 3],
            ['name' => 'ASISTEN STAF KHUSUS', 'status' => true, 'type' => 3],
            ['name' => 'SEKRETARIAT PADA STAF KHUSUS', 'status' => true, 'type' => 3],
            ['name' => 'ANGGOTA TIM AHLI', 'status' => true, 'type' => 3],
            ['name' => 'TNI/POLRI (TIM AJUDAN)', 'status' => true, 'type' => 3],
            ['name' => 'TNI/POLRI (TIM DOKTER PRIBADI)', 'status' => true, 'type' => 3],
            ['name' => 'TNI/POLRI (PENGEMUDI)', 'status' => true, 'type' => 3],
            ['name' => 'PEMBANTU ASISTEN STAF KHUSUS', 'status' => true, 'type' => 3],
            ['name' => 'STAF PADA SESPRI', 'status' => true, 'type' => 3],
            ['name' => 'TPPS', 'status' => true, 'type' => 3],
            ['name' => 'TNP2K', 'status' => true, 'type' => 3],
            ['name' => 'TNI/POLRI (PROTOKOL)', 'status' => true, 'type' => 3],
            ['name' => 'SESPRI', 'status' => true, 'type' => 3],
        ];
        DB::table('employment_types')->insertTs($employmentTypes);

    }
}
