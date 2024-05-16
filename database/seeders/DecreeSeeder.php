<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DecreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('decrees')->delete();
        $decrees = [
            ["name" => "Keputusan Presiden", "acronym" => "Keppres"],
            ["name" => "Keputusan Menteri Sekretaris Negara", "acronym" => "Kepmensesneg"],
            ["name" => "Keputusan Sekretaris Negara", "acronym" => "Kepsesneg"],
            ["name" => "Keputusan Sekretaris Wakil Presiden", "acronym" => "Kepseswapres"],
            ["name" => "Keputusan Menteri Perencanaan Pembangunan Nasional Bappenas", "acronym" => "Kepmenbappenas"],
            ["name" => "Keputusan Menteri PUPR", "acronym" => "Kepmenpupr"],
            ["name" => "Keputusan Kepala Staf Angkatan Laut", "acronym" => "Kepkasal"],
            ["name" => "Keputusan Kepala Staf Angkatan Darat", "acronym" => "Kepkasad"],
            ["name" => "Surat Telegram Kapolri", "acronym" => "STK"],
            ["name" => "Keputusan Kepala Badan Siber dan Sandi Negara", "acronym" => "Kepka BSSN"],
            ["name" => "Keputusan Menteri Koperasi dan Usaha Kecil dan Menengah", "acronym" => "Kepmenkoukm"],
            ["name" => "Keputusan Deputi Bidang Sumber Daya Manusia", "acronym" => "KepDeputi BidSDM"],
            ["name" => "Keputusan Menteri Hukum dan HAM", "acronym" => "Kepmenkumham"],
            ["name" => "Keputusan Kepala Staf Angkatan Udara", "acronym" => "Kepkasau"],
        ];
        DB::table('decrees')->insertTs($decrees);

    }
}
