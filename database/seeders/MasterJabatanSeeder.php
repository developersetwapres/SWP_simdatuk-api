<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jabatan')->insert([
            'nama' => 'Kepala Subbagian Acara',
            'jumlah_diperlukan' => 5,
            'eselon_id' => 3,
            'deputi_id' => 2,
            'biro_id' => 1,
            'bagian_id' => 1
        ]);
    }
}
