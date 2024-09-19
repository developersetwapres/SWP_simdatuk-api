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
            ["id" => 1, "name" => "Keputusan Presiden", "acronym" => "Keppres"],
            ["id" => 2, "name" => "Keputusan Menteri Sekretaris Negara", "acronym" => "Kepmensesneg"],
            ["id" => 3, "name" => "Keputusan Sekretaris Negara", "acronym" => "Kepsesneg"],
            ["id" => 4, "name" => "Keputusan Sekretaris Wakil Presiden", "acronym" => "Kepseswapres"],
            ["id" => 5, "name" => "Keputusan Menteri Perencanaan Pembangunan Nasional Bappenas", "acronym" => "Kepmenbappenas"],
            ["id" => 6, "name" => "Keputusan Menteri PUPR", "acronym" => "Kepmenpupr"],
            ["id" => 7, "name" => "Keputusan Kepala Staf Angkatan Laut", "acronym" => "Kepkasal"],
            ["id" => 8, "name" => "Keputusan Kepala Staf Angkatan Darat", "acronym" => "Kepkasad"],
            ["id" => 9, "name" => "Surat Telegram Kapolri", "acronym" => "STK"],
            ["id" => 10, "name" => "Keputusan Kepala Badan Siber dan Sandi Negara", "acronym" => "Kepka BSSN"],
            ["id" => 11, "name" => "Keputusan Menteri Koperasi dan Usaha Kecil dan Menengah", "acronym" => "Kepmenkoukm"],
            ["id" => 12, "name" => "Keputusan Deputi Bidang Sumber Daya Manusia", "acronym" => "KepDeputi BidSDM"],
            ["id" => 13, "name" => "Keputusan Menteri Hukum dan HAM", "acronym" => "Kepmenkumham"],
            ["id" => 14, "name" => "Keputusan Kepala Staf Angkatan Udara", "acronym" => "Kepkasau"],
            ["id" => 15, "name" => "Keputusan Menteri PPN", "acronym" => "KepmenPPN"],
            ["id" => 16, "name" => "Keputusan Koordinator Bidang Pembangunan Manusia dan Kebudayaan Republik Indonesia", "acronym" => "Kemenko PKM"],
            ["id" => 17, "name" => "SK Penghargaan Lainnya", "acronym" => "SK Penghargaan Lainnya"],
        ];
        DB::table('decrees')->insertTs($decrees);

    }
}
