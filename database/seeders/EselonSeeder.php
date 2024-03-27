<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EselonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('eselon')->insert([
            ['nama' => 'Eselon II.a'],
            ['nama' => 'Eselon IV'],
            ['nama' => 'Eselon IV.a'],
        ]);
    }
}
