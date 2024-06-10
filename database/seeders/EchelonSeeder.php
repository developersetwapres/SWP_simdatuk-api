<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EchelonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('echelons')->delete();
        $echelons = [
            ['id' => 1, 'name' => 'Eselon I'],
            ['id' => 2, 'name' => 'Eselon II'],
            ['id' => 3, 'name' => 'Eselon III'],
            ['id' => 4, 'name' => 'Eselon IV'],
            ['id' => 5, 'name' => 'Ahli Utama'],
            ['id' => 6, 'name' => 'Ahli Madya'],
            ['id' => 7, 'name' => 'Ahli Muda'],
            ['id' => 8, 'name' => 'Ahli Pertama'],
            ['id' => 9, 'name' => 'Pelaksana'],
            ['id' => 10, 'name' => 'Penyelia'],
            ['id' => 11, 'name' => 'Mahir'],
            ['id' => 12, 'name' => 'Terampil'],
            ['id' => 13, 'name' => 'Pemula'],
        ];
        DB::table('echelons')->insertTs($echelons);
    }
}
