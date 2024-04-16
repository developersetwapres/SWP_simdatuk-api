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
            ['name' => 'Kementerian Perindustrian dan Perdagangan'],
            ['name' => 'Kementerian Perdagangan'],
            ['name' => 'Kementerian Riset, Teknologi dan Pendidikan Tinggi'],
            ['name' => 'Kementerian Luar Negeri'],
            ['name' => 'Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan'],
            ['name' => 'Kementerian Keuangan'],
            ['name' => 'Kementerian Hukum dan HAM'],
            ['name' => 'Kementerian Dalam Negeri'],
            ['name' => 'Kementerian PPN/ Bappenas'],
            ['name' => 'Kementerian Sosial'],
            ['name' => 'Departemen Pekerjaan Umum'],
            ['name' => 'Departemen Luar Negeri'],
            ['name' => 'Departemen Dalam Negeri'],
            ['name' => 'Badan Pengawas Keuangan dan Pembangunan (BPKP)'],
            ['name' => 'Lembaga Ilmu Pengetahuan Indonesia (LIPI)'],
            ['name' => 'Perpustakaan Nasional'],
            ['name' => 'Lembaga Administrasi Negara (LAN)'],
            ['name' => 'Badan Kepegawaian Negara (BKN)'],
            ['name' => 'Pemerintah Kota Surabaya Provinsi Jawa Timur'],
            ['name' => 'Sekretariat Militer Presiden'],
            ['name' => 'Markas Besar TNI'],
            ['name' => 'Pasukan Pengamanan Presiden (Paspampres)'],
            ['name' => 'Pemerintah Provinsi DKI Jakarta'],
            ['name' => 'Markas Besar POLRI'],
            ['name' => 'Kementerian Pendidikan dan Kebudayaan'],
            ['name' => 'Badan Siber dan Sandi Negara'],
        ];
        DB::table('institutions')->insertTs($institutions);
    }
}
