<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubbagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subbagian')->insert([
            [
                'nama' => 'Subbagian Example',
                'deputi_id' => 2
            ],
            [
                'nama' => 'Subbagian Example 2',
                'deputi_id' => 2
            ],
            [
                'nama' => 'Subbagian Example 3',
                'deputi_id' => 2
            ],
        ]);
    }
}
