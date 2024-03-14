<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'group' => '',
                'name' => 'Dasboard',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Komposisi Pegawai',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Pegawai ASN',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Pegawai Non ASN',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Pegawai Outsourcing',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Peta Jabatan',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Bandingkan Pegawai',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Rekapitulasi',
                'name' => 'Promosi Pegawai',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Data Pegawai',
                'name' => 'ASN',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Pegawai',
                'name' => 'Non ASN',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Pegawai',
                'name' => 'Outsourcing',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Jabatan',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Golongan',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Gaji',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Pelatihan Struktural',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Pelatihan Fungsional',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Pelatihan Teknis',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Penghargaan',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'SKP',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Penilaian Prestasi Kerja',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Data Riwayat',
                'name' => 'Hukuman Disiplin',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Pengguna',
                'permitted_actions' => 'rcu'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Role Pengguna',
                'permitted_actions' => 'rcud'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Jabatan',
                'permitted_actions' => 'rcud'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Golongan',
                'permitted_actions' => 'r'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Instansi',
                'permitted_actions' => 'rcud'
            ],
            [
                'group' => 'Master Data',
                'name' => 'Data Perguruan Tinggi',
                'permitted_actions' => 'rcud'
            ],
            [
                'group' => 'Export',
                'name' => 'Data Instansi',
                'permitted_actions' => 'cu'
            ]
        ];

        DB::table('permissions')->insert($data);
    }
}
