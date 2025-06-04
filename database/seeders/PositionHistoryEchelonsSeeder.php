<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionHistoryEchelonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('position_history_echelons')->delete();
        $echelons = [
            ['id' => 1, 'name' => 'Eselon I'],
            ['id' => 2, 'name' => 'Eselon II'],
            ['id' => 3, 'name' => 'Eselon III'],
            ['id' => 4, 'name' => 'Eselon IV'],
            ['id' => 5, 'name' => 'Pelaksana'],
            ['id' => 6, 'name' => 'Fungsional'],
            ['id' => 7, 'name' => 'Staf'],
        ];
        DB::table('position_history_echelons')->insertTs($echelons);
    }
}
