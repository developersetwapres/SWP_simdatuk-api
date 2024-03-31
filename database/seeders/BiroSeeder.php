<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('biro')->insert([
            'nama' => 'Protokol dan Kerumahtanggaan',
            'deputi_id' => 2
        ]);
    }
}
