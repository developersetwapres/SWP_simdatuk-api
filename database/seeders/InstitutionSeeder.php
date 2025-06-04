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
            ['id' => 1, 'name' => 'Kementerian Sekretariat Negara'],
            ['id' => 2, 'name' => 'Kementerian Perindustrian dan Perdagangan'],
            ['id' => 3, 'name' => 'Kementerian Perdagangan'],
            ['id' => 4, 'name' => 'Kementerian Riset, Teknologi dan Pendidikan Tinggi'],
            ['id' => 5, 'name' => 'Kementerian Luar Negeri'],
            ['id' => 6, 'name' => 'Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan'],
            ['id' => 7, 'name' => 'Kementerian Keuangan'],
            ['id' => 8, 'name' => 'Kementerian Hukum dan HAM'],
            ['id' => 9, 'name' => 'Kementerian Dalam Negeri'],
            ['id' => 10, 'name' => 'Kementerian PPN/ Bappenas'],
            ['id' => 11, 'name' => 'Kementerian Sosial'],
            ['id' => 12, 'name' => 'Departemen Pekerjaan Umum'],
            ['id' => 13, 'name' => 'Departemen Luar Negeri'],
            ['id' => 14, 'name' => 'Departemen Dalam Negeri'],
            ['id' => 15, 'name' => 'Badan Pengawas Keuangan dan Pembangunan (BPKP)'],
            ['id' => 16, 'name' => 'Lembaga Ilmu Pengetahuan Indonesia (LIPI)'],
            ['id' => 17, 'name' => 'Perpustakaan Nasional'],
            ['id' => 18, 'name' => 'Lembaga Administrasi Negara (LAN)'],
            ['id' => 19, 'name' => 'Badan Kepegawaian Negara (BKN)'],
            ['id' => 20, 'name' => 'Pemerintah Kota Surabaya Provinsi Jawa Timur'],
            ['id' => 21, 'name' => 'Sekretariat Militer Presiden'],
            ['id' => 22, 'name' => 'Markas Besar TNI'],
            ['id' => 23, 'name' => 'Pasukan Pengamanan Presiden (Paspampres)'],
            ['id' => 24, 'name' => 'Pemerintah Provinsi DKI Jakarta'],
            ['id' => 25, 'name' => 'Markas Besar POLRI'],
            ['id' => 26, 'name' => 'Kementerian Pendidikan dan Kebudayaan'],
            ['id' => 27, 'name' => 'Badan Siber dan Sandi Negara'],
        ];
        DB::table('institutions')->insertTs($institutions);
    }
}
