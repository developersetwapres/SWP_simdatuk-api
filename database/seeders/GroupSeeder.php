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
            // Rumpun Riwayat Pegawai
            ["name" => "Pengelolaan Sumber Daya Manusia", "type" => 1],
            ["name" => "Kelembagaan dan Tata Laksana", "type" => 1],
            ["name" => "Pengembangan Kompetensi ", "type" => 1],
            ["name" => "Bantuan Hukum", "type" => 1],
            ["name" => "Perencanaan Program dan Anggaran", "type" => 1],
            ["name" => "Akuntabilitas Kinerja", "type" => 1],
            ["name" => "Keuangan", "type" => 1],
            ["name" => "Audit Internal", "type" => 1],
            ["name" => "Tata Usaha", "type" => 1],
            ["name" => "Arsip", "type" => 1],
            ["name" => "Perpustakaan", "type" => 1],
            ["name" => "Perancangan Grafis", "type" => 1],
            ["name" => "Pengelolaan Bangunan", "type" => 1],
            ["name" => "Pengelolaan Kendaraan serta Perlengkapan dan Peralatan", "type" => 1],
            ["name" => "Kerumahtanggaan/Tata Gerha", "type" => 1],
            ["name" => "Pengamanan Dalam", "type" => 1],
            ["name" => "Pelayanan Medis", "type" => 1],
            ["name" => "Sistem Informasi", "type" => 1],
            ["name" => "Infrastruktur", "type" => 1],
            ["name" => "Pengelolaan Informasi Publik", "type" => 1],
            ["name" => "Peliputan dan Dokumentasi", "type" => 1],
            ["name" => "Protokol VVIP dan VIP", "type" => 1],
            ["name" => "Ajudan VVIP dan VIP", "type" => 1],
            ["name" => "Pengelolaan Benda Seni dan Budaya", "type" => 1],
            ["name" => "Pengamanan VIP dan VVIP", "type" => 1],
            ["name" => "Analisis Kebijakan", "type" => 1],
            ["name" => "Analisis Pengaduan Masyarakat", "type" => 1],
            ["name" => "Analisis Hukum dan Perundang-undangan", "type" => 1],
            ["name" => "Kerja Sama Teknik dan Tenaga Asing", "type" => 1],
            ["name" => "Penetapan Pengangkatan/Pemberhentian/ Pensiun/Cuti bagi Pejabat Negara/Pejabat Pemerintah/Perwira TNI/POLRI", "type" => 1],
            ["name" => "Penetapan Gelar, Tanda Jasa, dan Tanda Kehormatan", "type" => 1],
            ["name" => "Perjalanan Dinas Luar Negeri", "type" => 1],

            // Rumpun Pelatihan Teknis
            ["name" => "Pengelolaan Sumber Daya Manusia", "type" => 2],
            ["name" => "Kelembagaan dan Tata Laksana", "type" => 2],
            ["name" => "Pengembangan Kompetensi ", "type" => 2],
            ["name" => "Perencanaan Program dan Anggaran", "type" => 2],
            ["name" => "Akuntabilitas Kinerja", "type" => 2],
            ["name" => "Keuangan", "type" => 2],
            ["name" => "Audit Internal", "type" => 2],
            ["name" => "Tata Usaha", "type" => 2],
            ["name" => "Arsip", "type" => 2],
            ["name" => "Perpustakaan", "type" => 2],
            ["name" => "Pengelolaan Bangunan dan Infrastruktur Lainnya", "type" => 2],
            ["name" => "Pengelolaan Kendaraan", "type" => 2],
            ["name" => "Pengelolaan Perlengkapan dan Peralatan", "type" => 2],
            ["name" => "Tata Gerha dan Jamuan", "type" => 2],
            ["name" => "Pengamanan Dalam", "type" => 2],
            ["name" => "Pelayanan Medis", "type" => 2],
            ["name" => "Sistem Informasi", "type" => 2],
            ["name" => "Infrastruktur", "type" => 2],
            ["name" => "Pengelolaan Informasi Publik", "type" => 2],
            ["name" => "Peliputan dan Dokumentasi", "type" => 2],
            ["name" => "Perancangan Grafis", "type" => 2],
            ["name" => "Protokol VVIP dan VIP", "type" => 2],
            ["name" => "Ajudan VVIP dan VIP", "type" => 2],
            ["name" => "Pengelolaan Benda Seni dan Budaya", "type" => 2],
            ["name" => "Pengamanan VIP dan VVIP", "type" => 2],
            ["name" => "Analisis Kebijakan", "type" => 2],
            ["name" => "Analisis Pengaduan Masyarakat", "type" => 2],
            ["name" => "Kerja Sama Teknik dan Tenaga Asing", "type" => 2],
            ["name" => "Analisis Hukum", "type" => 2],
            ["name" => "Analisis Perundang-undangan", "type" => 2],
            ["name" => "Penetapan Pengangkatan/Pemberhentian/ Pensiun/Cuti bagi Pejabat Negara/Pejabat Pemerintah/Perwira TNI/POLRI", "type" => 2],            
            ["name" => "Penetapan Gelar, Tanda Jasa, dan Tanda Kehormatan", "type" => 2],
            ["name" => "Perjalanan Dinas Luar Negeri", "type" => 2],
        ];
        DB::table('groups')->insertTs($groups);

    }
}
