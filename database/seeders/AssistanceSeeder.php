<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('assistances')->delete();
        $assistances = [
            ['name' => 'Staf Khusus', 'status' => true],
            ['name' => 'Sespri', 'status' => true],
            ['name' => 'Anggota Tim Ahli', 'status' => true],
            ['name' => 'Asisten Staf Khusus', 'status' => true],
            ['name' => 'Pembantu Asisten StfKss', 'status' => true],
            ['name' => 'Staf pada Sespri', 'status' => true],
            ['name' => 'Sekretariat pada StfKss', 'status' => true],
            ['name' => 'Asisten/Staf', 'status' => true],
            ['name' => 'TNP2K', 'status' => true],
            ['name' => 'TPPS', 'status' => true],
            ['name' => 'TNI/POLRI (tmDokpri)', 'status' => true],
            ['name' => 'TNI/POLRI (tmAjudan)', 'status' => true],
            ['name' => 'TNI/POLRI (psProtokol)', 'status' => true],
            ['name' => 'TNI/POLRI (psKeamanan)', 'status' => true],
            ['name' => 'TNI/POLRI (psPengemudi)', 'status' => true],
        ];
        DB::table('assistances')->insertTs($assistances);

    }
}
