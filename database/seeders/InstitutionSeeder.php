<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('institutions')->delete();
        $institutions = [
            ['name' => 'Kementerian Sekretariat Negara'],
            ['name' => 'Sekretariat Negara Wakil Presiden'],
        ];
        DB::table('institutions')->insertTs($institutions);
    }
}
