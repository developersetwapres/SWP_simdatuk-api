<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->delete();
        $disciplinaryTypes = [
            [
                'name' => 'Kepala Sekretariat Wakil Presiden',
            ],
            [
                'name' => 'Deputi Bidang Dukungan Kebijakan Pembangunan Ekonomi dan Peningkatan Daya Saing',
            ],
            [
                'name' => 'Deputi Bidang Dukungan Kebijakan Pembangunan Manusia dan Pemerataan Pembangunan',
            ],
            [
                'name' => 'Deputi Bidang Dukungan Kebijakan Pemerintah dan Wawasan Kebangsaan',
            ],
            [
                'name' => 'Deputi Bidang Administrasi',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Umum)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Komunikasi dan Informasi)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Politik dan Hubungan Antar Lembaga)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Ekonomi dan Keuangan)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Infrastuktur dan Investasi)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Reformasi Birokrasi)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden (Bidang Penanggulangan Kemiskinan dan Otonomi Daerah)',
            ],
            [
                'name' => 'Staff Khusus Wakil Presiden',
            ],
            [
                'name' => 'Asisten Staff Khusus Wakil Presiden',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS I)',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS II)',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS III)',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS IV)',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS V)',
            ],
            [
                'name' => 'Pembantu Asisten Staff Khusus Wakil Presiden (BANAS VI)',
            ],
            [
                'name' => 'Ajudan Wakil Presiden',
            ],
            [
                'name' => 'Ajudan Isteri Wakil Presiden',
            ],
            [
                'name' => 'Dokter Pribadi Wakil Presiden',
            ],
            [
                'name' => 'Anggota Tim Ahli Wakil Presiden',
            ],
            [
                'name' => 'Staff Pendukung Wakil Presiden',
            ],
            [
                'name' => 'Asisten Deputi Ekonomi dan Keuangan',
            ],
            [
                'name' => 'Asisten Deputi Industri, Perdagangan, Pariwisata, dan Ekonomi Kreatif',
            ],
            [
                'name' => 'Asisten Deputi Infrastruktur, Ketahanan Energi, dan Sumber Daya Alam',
            ],
            [
                'name' => 'Kepala Subbagian Dukungan Administrasi',
            ],
            [
                'name' => 'Asisten Deputi Penanggulangan Kemiskinan',
            ],
            [
                'name' => 'Asisten Deputi Pembangunan Sumber Daya Manusia',
            ],
            [
                'name' => 'Asisten Deputi Pemberdayaan Masyarakat dan Penanggulangan Bencana',
            ],
            [
                'name' => 'Kepala Bagian Dukungan Adminstrasi',
            ],
            [
                'name' => 'Kepala Subbagian Dukungan Adminstrasi',
            ],
            [
                'name' => 'Kepala Biro Protokol dan Kerumahtanggaan',
            ],
            [
                'name' => 'Kepala Biro Pers, Media, dan Informasi',
            ],
            [
                'name' => 'Kepala Biro Perencanaan dan Keuangan',
            ],
            [
                'name' => 'Kepala Biro Tata Usaha, Teknologi Informasi dan Kepegawaian',
            ],
            [
                'name' => 'Kepala Biro Umum',
            ],
            [
                'name' => 'Kepala Bagian Protokol',
            ],
            [
                'name' => 'Kepala Bagian Kerumahtanggaan',
            ],
            [
                'name' => 'Kepala Bagian Perjalanan',
            ],
            [
                'name' => 'Kepala Subbagian Acara',
            ],
            [
                'name' => 'Kepala Subbagian Pelayanan Protokol',
            ],
            [
                'name' => 'Kepala Subbagian Persidangan',
            ],
            [
                'name' => 'Ahli Tata Usaha',
            ],
            [
                'name' => 'Petugas Protokol Kepresidenan',
            ],
            [
                'name' => 'Analis Protokol',
            ],
        ];
        DB::table('positions')->insertTs($disciplinaryTypes);

    }
}
