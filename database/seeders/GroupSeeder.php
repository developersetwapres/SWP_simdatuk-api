<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('groups')->delete();
        $groups = [
            ["name" => "Pengelolaan Sumber Daya Manusia "],
            ["name" => "Kelembagaan dan Tata Laksana"],
            ["name" => "Pengembangan Kompetensi "],
            ["name" => "Bantuan Hukum"],
            ["name" => "Perencanaan Program dan Anggaran"],
            ["name" => "Akuntabilitas Kinerja"],
            ["name" => "Keuangan"],
            ["name" => "Audit Internal"],
            ["name" => "Tata Usaha"],
            ["name" => "Arsip"],
            ["name" => "Perpustakaan"],
            ["name" => "Perancangan Grafis"],
            ["name" => "Pengelolaan Bangunan"],
            ["name" => "Pengelolaan Kendaraan serta Perlengkapan dan Peralatan"],
            ["name" => "Kerumahtanggaan/Tata Gerha"],
            ["name" => "Pengamanan Dalam"],
            ["name" => "Pelayanan Medis"],
            ["name" => "Sistem Informasi"],
            ["name" => "Infrastruktur"],
            ["name" => "Pengelolaan Informasi Publik"],
            ["name" => "Peliputan dan Dokumentasi"],
            ["name" => "Protokol VVIP dan VIP"],
            ["name" => "Ajudan VVIP dan VIP"],
            ["name" => "Pengelolaan Benda Seni dan Budaya"],
            ["name" => "Pengamanan VIP dan VVIP"],
            ["name" => "Analisis Kebijakan"],
            ["name" => "Analisis Pengaduan Masyarakat"],
            ["name" => "Analisis Hukum dan Perundang-undangan"],
            ["name" => "Kerja Sama Teknik dan Tenaga Asing"],
            ["name" => "Penetapan Pengangkatan/Pemberhentian/ Pensiun/Cuti bagi Pejabat Negara/Pejabat Pemerintah/Perwira TNI/POLRI"],
            ["name" => "Penetapan Gelar, Tanda Jasa, dan Tanda Kehormatan"],
            ["name" => "Perjalanan Dinas Luar Negeri"],
        ];
        DB::table('groups')->insertTs($groups);

    }
}
