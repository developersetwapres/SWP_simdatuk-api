<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permissions')->delete();
        $permission = [
            [
                'name' => 'Rekapitulasi - Komposisi Pegawai',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Pegawai ASN',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Pegawai Non ASN',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Pegawai Outsourcing',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Peta Jabatan',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Bandingkan Pegawai',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Rekapitulasi - Promosi Pegawai',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Data Pegawai - ASN',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Pegawai - Non ASN',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Pegawai - Outsourcing',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Jabatan',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Golongan',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Pelatihan Struktural',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Pelatihan Fungsional',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Pelatihan Teknis',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Penghargaan',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - SKP',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Penilaian Prestasi Kerja',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Data Riwayat - Hukuman Disiplin',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Master Data - Data Pengguna',
                'permitted_actions' => 'cru',
            ],
            [
                'name' => 'Master Data - Data Role Pengguna',
                'permitted_actions' => 'crud',
            ],
            [
                'name' => 'Master Data - Data Jabatan',
                'permitted_actions' => 'crud',
            ],
            [
                'name' => 'Master Data - Data Golongan',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Master Data - Data Instansi',
                'permitted_actions' => 'crud',
            ],
            [
                'name' => 'Export',
                'permitted_actions' => 'r',
            ],
            [
                'name' => 'Catatan',
                'permitted_actions' => 'crud',
            ],
            [
                'name' => 'Hasil Talent Pool',
                'permitted_actions' => 'crud',
            ],
        ];
        DB::table('permissions')->insertTs($permission);
    }
}
