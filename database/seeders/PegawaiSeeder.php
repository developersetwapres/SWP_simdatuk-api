<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pegawai')->insert([
            'file_foto_profile' => 'example.jpg',
            'nama' => 'super admin',
            'nip' => 'super0001',
            'nrp' => 'super1000',
            'tempat_lahir' => 'depok',
            'tanggal_lahir' => date("Y-m-d"),
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'status_perkawinan' => 'duda',
            'golongan' => 'test',
            'tmt_golongan' => 'test',
            'jabatan' => 'test',
            'eselon' => 'test',
            'tmt_eselon' => 'test',
            'instansi_induk' => 'test',
            'satuan_organisasi' => 'test',
            'unit_kerja' => 'test',
            'no_karpeg' => 'test',
            'no_karis' => 'test',
            'no_karsu' => 'test',
            'npwp' => 'test',
            'status_pegawai' => 'test',
            'komplek' => 'test',
            'alamat_tempat_tinggal_saat_ini' => 'test',
            'no_telepon_rumah' => 'test',
            'no_hp' => 'test',
            'alamat_kantor' => 'test',
            'no_telepon_kantor' => 'test',
            'email' => 'super@admin.com',
            'type' => 'test',
        ]);
    }
}
