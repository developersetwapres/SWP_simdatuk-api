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
            ['id' => 5, 'name' => 'JF Ahli Utama'],
            ['id' => 6, 'name' => 'JF Ahli Madya'],
            ['id' => 7, 'name' => 'JF Ahli Muda'],
            ['id' => 8, 'name' => 'JF Ahli Pertama'],
            ['id' => 9, 'name' => 'Pelaksana'],
            ['id' => 10, 'name' => 'JF Penyelia'],
            ['id' => 11, 'name' => 'JF Mahir'],
            ['id' => 12, 'name' => 'JF Terampil'],
            ['id' => 13, 'name' => 'JF Pemula'],
        ];
        DB::table('echelons')->insertTs($echelons);
    }
}
