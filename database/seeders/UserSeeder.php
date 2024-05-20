<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        // Added administrator
        $role = DB::table('roles')->select('id')->where('name', 'administrator')->first();

        // Set default password
        $hashedPassword = Hash::make('password');

        // Get pegawai swp
        $asn = "
            INSERT INTO
                simdatuk.users (
                    role_id,
                    email,
                    username,
                    password,
                    title_prefix,
                    name,
                    title_suffix,
                    photo_profile,
                    id_number,
                    employee_id_number,
                    employee_registration_number,
                    place_of_birth,
                    date_of_birth,
                    religion,
                    gender,
                    marital_status,
                    employment_type_id,
                    grade_effective_date,
                    position_effective_date,
                    organization_id,
                    employee_id_card_number,
                    wife_id_card_number,
                    husband_id_card_number,
                    id_tax,
                    current_address,
                    home_phone_number,
                    mobile_phone,
                    office_phone_number,
                    type,
                    status,
                    created_at
                )
            SELECT
                IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', '$role->id', NULL),
                CASE
                    WHEN email = NULL THEN NULL
                    WHEN email = '' THEN NULL
                    WHEN email = '-' THEN NULL
                    ELSE LOWER(email)
                END AS email,
                CONCAT(SUBSTRING(LOWER(REPLACE(REPLACE(nm_pegawai, '.', ''), ' ', '')), 1, 5), DATE_FORMAT(DATE(tgl_lahir), '%y%m%d')) as username,
                IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', '$hashedPassword', NULL) AS password,
                gelar_dpn,
                nm_pegawai,
                gelar_blk,
                CONCAT('photo_profile/', id_pegawai, '.jpg') AS photo_profile,
                no_nik,
                id_pegawai,
                IF(nip_lama = '', id_pegawai, nip_lama),
                tmpt_lahir,
                tgl_lahir,
                CASE
                    WHEN agama = 'Islam' THEN '1'
                    WHEN agama = 'Hindu' THEN '4'
                    WHEN agama = 'Katholik' THEN '3'
                    WHEN agama = 'Protestan' THEN '2'
                    ELSE NULL
                END AS religion,
                CASE
                    WHEN kelamin = 'Laki-laki' THEN '1'
                    WHEN kelamin = 'Perempuan' THEN '0'
                    ELSE NULL
                END AS gender,
                CASE
                    WHEN status_nkh = 'Belum Kawin' THEN '1'
                    WHEN status_nkh = 'Kawin' THEN '2'
                    WHEN status_nkh = 'Cerai' THEN '4'
                    WHEN status_nkh = 'Cerai Hidup' THEN '3'
                    ELSE NULL
                END AS marital_staus,
                CASE
                    WHEN jns_kepeg = 'TNI/POLRI' THEN '1'
                    WHEN jns_kepeg = 'Sipil' THEN '2'
                    WHEN jns_kepeg = 'Organik' THEN '3'
                    WHEN jns_kepeg = 'PPPK' THEN '4'
                    ELSE NULL
                END AS employment_type_id,
                tmt_golongan,
                tmt_jabatan,
                '1',
                no_karpeg,
                no_karisu,
                no_karisu,
                npwp,
                al_rumah,
                tlp_rumah,
                no_seluler,
                tlp_kntr,
                '1',
                IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', TRUE, FALSE) AS status,
                CURRENT_TIMESTAMP AS created_at
            FROM
                simdatuk_dump.tbl_1pegawai_swp
        ";

        DB::statement($asn);

        // Get pegawai perbantuan
        $nonAsn = "
            INSERT INTO
                simdatuk.users (
                    email,
                    title_prefix,
                    name,
                    title_suffix,
                    id_number,
                    employee_id_number,
                    employee_registration_number,
                    place_of_birth,
                    date_of_birth,
                    religion,
                    gender,
                    employment_type_id,
                    grade_effective_date,
                    position_effective_date,
                    organization_id,
                    current_address,
                    type,
                    created_at
                )
            SELECT
                CASE
                    WHEN email = NULL THEN NULL
                    WHEN email = '' THEN NULL
                    WHEN email = '-' THEN NULL
                    ELSE LOWER(email)
                END AS email,
                gelar_dpn,
                nm_perbantuan,
                gelar_blk,
                no_ktp,
                id_perbantuan,
                IF(id_lama = '', id_perbantuan, id_lama),
                tmpt_lahir,
                tgl_lahir,
                CASE
                    WHEN agama = 'Islam' THEN '1'
                    WHEN agama = 'Kristen P' THEN '2'
                    WHEN agama = 'Protestan' THEN '2'
                    WHEN agama = 'Budha' THEN '5'
                    WHEN agama = 'Katholik' THEN '3'
                    ELSE NULL
                END AS religion,
                CASE
                    WHEN kelamin = 'Laki-laki' THEN '1'
                    WHEN kelamin = 'Perempuan' THEN '0'
                    ELSE NULL
                END AS gender,
                CASE
                    WHEN jns_perbantuan = 'Staf Khusus' THEN '5'
                    WHEN jns_perbantuan = 'Asisten/Staf' THEN '6'
                    WHEN jns_perbantuan = 'Asisten Staf Khusus' THEN '7'
                    WHEN jns_perbantuan = 'Sekretariat pada StfKss' THEN '8'
                    WHEN jns_perbantuan = 'Anggota Tim Ahli' THEN '9'
                    WHEN jns_perbantuan = 'TNI/POLRI (tmAjudan)' THEN '10'
                    WHEN jns_perbantuan = 'TNI/POLRI (tmDokpri)' THEN '11'
                    WHEN jns_perbantuan = 'TNI/POLRI (psPengemudi)' THEN '12'
                    WHEN jns_perbantuan = 'Pembantu Asisten StfKss' THEN '13'
                    WHEN jns_perbantuan = 'Staf pada Sespri' THEN '14'
                    WHEN jns_perbantuan = 'TPPS' THEN '15'
                    WHEN jns_perbantuan = 'TNP2K' THEN '16'
                    WHEN jns_perbantuan = 'TNI/POLRI (psProtokol)' THEN '17'
                    WHEN jns_perbantuan = 'Sespri' THEN '18'
                    ELSE NULL
                END AS employment_type_id,
                tmt_golpangkat,
                tmt_jabatan,
                '1',
                alamat,
                '2',
                CURRENT_TIMESTAMP AS created_at
            FROM
                simdatuk_dump.tbl_2perbantuan
        ";

        DB::statement($nonAsn);

        // Get pegawai outsource
        $outsource = "
            INSERT INTO
                simdatuk.users (
                    email,
                    name,
                    id_number,
                    employee_id_number,
                    place_of_birth,
                    date_of_birth,
                    religion,
                    gender,
                    employment_type_id,
                    organization_id,
                    current_address,
                    type,
                    created_at
                )
            SELECT
                CASE
                    WHEN email = NULL THEN NULL
                    WHEN email = '' THEN NULL
                    WHEN email = '-' THEN NULL
                    ELSE LOWER(email)
                END AS email,
                nm_outsorce,
                no_ktp,
                id_outsorce,
                tmp_lahir,
                tgl_lahir,
                CASE
                    WHEN agama = 'Islam' THEN '1'
                    WHEN agama = 'Protestan' THEN '2'
                    WHEN agama = 'Katholik' THEN '3'
                    ELSE NULL
                END AS religion,
                CASE
                    WHEN kelamin = 'Laki-laki' THEN '1'
                    WHEN kelamin = 'Perempuan' THEN '0'
                    ELSE NULL
                END AS gender,
                CASE
                    WHEN jns_outsorce = 'outsorce' THEN '19'
                    WHEN jns_outsorce = 'rekanan' THEN '20'
                    ELSE NULL
                END AS employment_type_id,
                '1',
                alamat,
                '3',
                CURRENT_TIMESTAMP AS created_at
            FROM
                simdatuk_dump.tbl_3outsorce
        ";

        DB::statement($outsource);
    }
}
