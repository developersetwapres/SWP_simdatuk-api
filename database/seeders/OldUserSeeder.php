<?php

namespace Database\Seeders;

use App\Helpers\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OldUserSeeder extends Seeder
{
    use Document;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('users')->delete();

            // Added administrator
            $role = DB::table('roles')->select('id')->where('name', 'administrator')->first();

            // Set default password
            $hashedPassword = Hash::make('password');

            // Get pegawai swp
            $asn = "
                SELECT
                    IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', '$role->id', NULL) as role_id,
                        CASE
                        WHEN email = NULL THEN NULL
                        WHEN email = '' THEN NULL
                        WHEN email = '-' THEN NULL
                        ELSE LOWER(email)
                    END AS email,
                    CONCAT(SUBSTRING(LOWER(REPLACE(REPLACE(nm_pegawai, '.', ''), ' ', '')), 1, 5), DATE_FORMAT(DATE(tgl_lahir), '%y%m%d')) as username,
                    IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', '$hashedPassword', NULL) AS password,
                    CASE
                        WHEN gelar_dpn = '' THEN NULL
                        ELSE gelar_dpn
                    END AS title_prefix,
                    nm_pegawai as name,
                    CASE
                        WHEN gelar_blk = '' THEN NULL
                        ELSE gelar_blk
                    END AS title_suffix,
                    CONCAT('photo_profile/', id_pegawai, '.jpg') AS photo_profile,
                    id_pegawai as employee_id_number,
                    IF(nip_lama = '', id_pegawai, nip_lama) as employee_registration_number,
                    tmpt_lahir as place_of_birth,
                    tgl_lahir as date_of_birth,
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
                    END AS marital_status,
                    CASE
                        WHEN jns_kepeg = 'TNI/POLRI' THEN '1'
                        WHEN jns_kepeg = 'Sipil' THEN '2'
                        WHEN jns_kepeg = 'Organik' THEN '3'
                        WHEN jns_kepeg = 'PPPK' THEN '4'
                        ELSE NULL
                    END AS employment_type_id,
                    CASE
                        WHEN id_golongan = '02' THEN 16
                        WHEN id_golongan = '03' THEN 15
                        WHEN id_golongan = '04' THEN 14
                        WHEN id_golongan = '05' THEN 13
                        WHEN id_golongan = '06' THEN 12
                        WHEN id_golongan = '07' THEN 11
                        WHEN id_golongan = '08' THEN 10
                        WHEN id_golongan = '09' THEN 9
                        WHEN id_golongan = '10' THEN 8
                        WHEN id_golongan = '11' THEN 7
                        WHEN id_golongan = '12' THEN 6
                        WHEN id_golongan = '13' THEN 5
                        WHEN id_golongan = '14' THEN 4
                        WHEN id_golongan = '15' THEN 3
                        WHEN id_golongan = '16' THEN 2
                        WHEN id_golongan = '17' THEN 1
                        WHEN id_golongan = '24' THEN 28
                        WHEN id_golongan = '26' THEN 26
                        ELSE NULL
                    END AS grade_id,
                    tmt_golongan as grade_effective_date,
                    tmt_jabatan as position_effective_date,
                    '1' as organization_id,
                    CASE
                        WHEN pddk_akhir = 'Diploma IV/Strata I' THEN '6'
                        WHEN pddk_akhir = 'SLTA/Sederajat' THEN '3'
                        WHEN pddk_akhir = 'Strata II' THEN '7'
                        WHEN pddk_akhir = 'Strata III' THEN '8'
                        WHEN pddk_akhir = 'SLTP/Sederajat' THEN '2'
                        WHEN pddk_akhir = 'SD/Sederajat' THEN '1'
                        WHEN pddk_akhir = 'Akademi/D3/S.Muda' THEN '5'
                        WHEN pddk_akhir = 'Diploma I/II' THEN '4'
                        ELSE NULL
                    END AS education_level,
                    nm_sekolah AS education_name,
                    CASE
                        WHEN thn_lulus = '' THEN NULL
                        WHEN thn_lulus < 1900 THEN NULL
                        ELSE thn_lulus
                    END AS education_year,
                    no_karpeg as employee_id_card_number,
                    no_karisu as karisu_number,
                    npwp as id_tax,
                    CASE
                        WHEN status_kepeg = 'Aktif' THEN 1
                        WHEN status_kepeg = 'Pensiun' THEN 2
                        WHEN status_kepeg = 'Berhenti' THEN 3
                        WHEN status_kepeg = 'Meninggal' THEN 4
                        WHEN status_kepeg = 'Alih Status' THEN 5
                        WHEN status_kepeg = 'Aktif_PS' THEN 6
                        WHEN status_kepeg = 'CLTN' THEN 7
                        WHEN status_kepeg = 'TBL' THEN 8
                        WHEN status_kepeg = 'Non Aktif' THEN 9
                    END AS employment_status,
                    no_nik as id_number,
                    no_kk as family_registration_number,
                    CASE
                        WHEN id_komplek = 'LK' THEN 1
                        WHEN id_komplek = 'DKG' THEN 4
                        WHEN id_komplek = 'DKI' THEN 2
                        WHEN id_komplek = 'DKC' THEN 8
                        WHEN id_komplek = 'DKA' THEN 10
                        WHEN id_komplek = 'DKF' THEN 5
                        WHEN id_komplek = 'DKE' THEN 6
                        WHEN id_komplek = 'DKB' THEN 9
                        WHEN id_komplek = 'DKD' THEN 7
                        WHEN id_komplek = 'DKH' THEN 3
                        ELSE NULL
                    END AS residence_id,
                    al_rumah as current_address,
                    tlp_rumah as home_phone_number,
                    no_seluler as mobile_phone,
                    tlp_kntr as office_phone_number,
                    '1' as type,
                    IF(id_pegawai = '198503022009021001' OR id_pegawai = '198605282009122001', TRUE, FALSE) AS status,
                    CURRENT_TIMESTAMP AS created_at
                FROM
                    simdatuk_dump.tbl_1pegawai_swp
            ";

            $asn = DB::select($asn);
            foreach ($asn as $item) {
                $item->photo_profile = $this->getDocumentExist($item->photo_profile);
            }
            DB::table('users')->insertTs(json_decode(json_encode($asn), true));

            // Get pegawai perbantuan
            $nonAsn = "
                SELECT
                    CASE
                        WHEN email = NULL THEN NULL
                        WHEN email = '' THEN NULL
                        WHEN email = '-' THEN NULL
                        ELSE LOWER(email)
                    END AS email,
                    CASE
                        WHEN gelar_dpn = '' THEN NULL
                        ELSE gelar_dpn
                    END AS title_prefix,
                    nm_perbantuan AS name,
                    CASE
                        WHEN gelar_blk = '' THEN NULL
                        ELSE gelar_blk
                    END AS title_suffix,
                    id_perbantuan AS employee_id_number,
                    IF(id_lama = '', id_perbantuan, id_lama) AS employee_registration_number,
                    tmpt_lahir AS place_of_birth,
                    tgl_lahir AS date_of_birth,
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
                    tmt_golpangkat AS grade_effective_date,
                    tmt_jabatan AS position_effective_date,
                    '1' AS organization_id,
                    CASE
                        WHEN status_kepeg = 'Aktif' THEN 1
                        WHEN status_kepeg = 'Pensiun' THEN 2
                        WHEN status_kepeg = 'Berhenti' THEN 3
                        WHEN status_kepeg = 'Meninggal' THEN 4
                    END AS employment_status,
                    CASE
                        WHEN pddk_akhir = 'Akademi/D3/S.Muda' THEN '5'
                        WHEN pddk_akhir = 'Diploma I/II' THEN '4'
                        WHEN pddk_akhir = 'Diploma IV/Strata I' THEN '6'
                        WHEN pddk_akhir = 'SD/Sederajat' THEN '1'
                        WHEN pddk_akhir = 'SLTA/Sederajat' THEN '3'
                        WHEN pddk_akhir = 'SLTP/Sederajat' THEN '2'
                        WHEN pddk_akhir = 'Strata II' THEN '7'
                        WHEN pddk_akhir = 'Strata III' THEN '8'
                        ELSE NULL
                    END AS education_level,
                    IF(ket_sekolah = '', jurusan_pddk, ket_sekolah) AS education_name,
                    CASE
                        WHEN thn_lulus = '' THEN NULL
                        WHEN thn_lulus < 1900 THEN NULL
                        ELSE thn_lulus
                    END AS education_year,
                    no_ktp AS id_number,
                    alamat AS current_address,
                    tlp_rumah AS home_phone_number,
                    no_tlp AS mobile_phone,
                    '2' AS type,
                    CURRENT_TIMESTAMP AS created_at
                FROM
                    simdatuk_dump.tbl_2perbantuan
            ";

            $nonAsn = DB::select($nonAsn);
            DB::table('users')->insertTs(json_decode(json_encode($nonAsn), true));

            // Get pegawai outsource
            $outsource = "
                SELECT
                    CASE
                        WHEN db_lama_outsource.email = NULL THEN NULL
                        WHEN db_lama_outsource.email = '' THEN NULL
                        WHEN db_lama_outsource.email = '-' THEN NULL
                        ELSE LOWER(db_lama_outsource.email)
                    END AS email,
                    db_lama_outsource.nm_outsorce AS name,
                    db_lama_outsource.id_outsorce AS employee_id_number,
                    db_lama_outsource.tmp_lahir AS place_of_birth,
                    db_lama_outsource.tgl_lahir AS date_of_birth,
                    CASE
                        WHEN db_lama_outsource.agama = 'Islam' THEN '1'
                        WHEN db_lama_outsource.agama = 'Protestan' THEN '2'
                        WHEN db_lama_outsource.agama = 'Katholik' THEN '3'
                        ELSE NULL
                    END AS religion,
                    CASE
                        WHEN db_lama_outsource.kelamin = 'Laki-laki' THEN '1'
                        WHEN db_lama_outsource.kelamin = 'Perempuan' THEN '0'
                        ELSE NULL
                    END AS gender,
                    CASE
                        WHEN db_lama_outsource.jns_outsorce = 'outsorce' THEN '19'
                        WHEN db_lama_outsource.jns_outsorce = 'rekanan' THEN '20'
                        ELSE NULL
                    END AS employment_type_id,
                    db_baru_position.id as position_id,
                    '1' AS organization_id,
                    CASE
                        WHEN db_lama_outsource.status = 'Aktif' THEN 1
                        WHEN db_lama_outsource.status = 'Pensiun' THEN 2
                        WHEN db_lama_outsource.status = 'Berhenti' THEN 3
                        WHEN db_lama_outsource.status = 'Meninggal' THEN 4
                    END AS employment_status,
                    CASE
                        WHEN db_lama_outsource.pddk = 'Akademi/D3/S.Muda' THEN '5'
                        WHEN db_lama_outsource.pddk = 'Diploma I/II' THEN '4'
                        WHEN db_lama_outsource.pddk = 'Diploma IV/Strata I' THEN '6'
                        WHEN db_lama_outsource.pddk = 'SD/Sederajat' THEN '1'
                        WHEN db_lama_outsource.pddk = 'SLTA/Sederajat' THEN '3'
                        WHEN db_lama_outsource.pddk = 'SLTP/Sederajat' THEN '2'
                        WHEN db_lama_outsource.pddk = 'Strata II' THEN '7'
                        WHEN db_lama_outsource.pddk = 'Strata III' THEN '8'
                        ELSE NULL
                    END AS education_level,
                    IF(db_lama_outsource.nm_sekolah = '', db_lama_outsource.jurusan, db_lama_outsource.nm_sekolah) AS education_name,
                    CASE
                        WHEN db_lama_outsource.thn_lulus = '' THEN NULL
                        WHEN db_lama_outsource.thn_lulus < 1900 THEN NULL
                        ELSE db_lama_outsource.thn_lulus
                    END AS education_year,
                    db_lama_outsource.no_ktp AS id_number,
                    db_lama_outsource.no_kk AS family_registration_number,
                    db_lama_outsource.alamat AS current_address,
                    db_lama_outsource.ket_jabatan AS description,
                    '3' AS type,
                    CURRENT_TIMESTAMP AS created_at
                FROM
                    simdatuk_dump.tbl_3outsorce as db_lama_outsource
                LEFT JOIN
                    simdatuk.positions as db_baru_position
                ON
                    db_lama_outsource.jabatan = db_baru_position.name
                WHERE
                    db_baru_position.name
                IN
                    ('Pengemudi', 'Petugas Kebersihan Gedung', 'Petugas Perawatan Kolam', 'Petugas Taman', 'Pramusaji/Pramubakti', 'Teknisi Jaringan', 'Teknisi Komputer', 'Teknisi Mekanikal dan Elektrikal', 'Teknisi Fotocopy', 'Teknisi Road Blocker', 'Teknisi Lift')
            ";

            $outsource = DB::select($outsource);
            DB::table('users')->insertTs(json_decode(json_encode($outsource), true));
            $this->setPosition();
            $this->setEchelon();
            $this->setGrade();
        }
    }

    private static function setGrade()
    {
        $sql = "
            UPDATE simdatuk.users u
            JOIN (
                SELECT
                    du.nm_pegawai,
                    du.id_pegawai,
                    go.id_golongan,
                    go.pangkat,
                    u.employee_id_number
                FROM
                    simdatuk_dump.tbl_1pegawai_swp AS du
                JOIN
                    simdatuk_dump.tbl_mst_golongan AS go ON du.id_golongan = go.id_golongan
                LEFT JOIN
                    simdatuk.users u ON du.id_pegawai = u.employee_id_number
            )  AS subq ON u.employee_id_number = subq.employee_id_number
            LEFT JOIN simdatuk.grades AS g ON g.name = subq.pangkat
            SET u.grade_id = g.id
        ";

        DB::statement($sql);
    }

    private static function setEchelon()
    {
        $sql = "
            UPDATE simdatuk.users u
            JOIN (
                SELECT
                    du.nm_pegawai,
                    du.id_pegawai,
                    du.ket_eselon,
                    u.employee_id_number
                FROM
                    simdatuk_dump.tbl_1pegawai_swp AS du
                LEFT JOIN
                    simdatuk.users u ON du.id_pegawai = u.employee_id_number
            ) AS subq ON u.employee_id_number = subq.employee_id_number
            SET u.echelon_id =
                CASE
                    WHEN subq.ket_eselon = 'Eselon I' THEN 1
                    WHEN subq.ket_eselon = 'Eselon II' THEN 2
                    WHEN subq.ket_eselon = 'Eselon III' THEN 3
                    WHEN subq.ket_eselon = 'Eselon IV' THEN 4
                    WHEN subq.ket_eselon = 'Ahli Utama' THEN 5
                    WHEN subq.ket_eselon = 'Ahli Madya' THEN 6
                    WHEN subq.ket_eselon = 'Ahli Muda' THEN 7
                    WHEN subq.ket_eselon = 'Ahli Pertama' THEN 8
                    WHEN subq.ket_eselon = 'Pelaksana' THEN 9
                    WHEN subq.ket_eselon = 'Penyelia' THEN 10
                    WHEN subq.ket_eselon = 'Mahir' THEN 11
                    WHEN subq.ket_eselon = 'Terampil' THEN 12
                    WHEN subq.ket_eselon = 'Pemula' THEN 13
                    ELSE NULL
                END
        ";
        DB::statement($sql);
    }

    /**
     * Set position for employee
     *
     * @return void
     */
    private static function setPosition()
    {
        DB::table('users')->where('employee_id_number', '197303221997021001')->update([
            'position_id' => 2,
        ]);
        DB::table('users')->where('employee_id_number', '2019111401')->update([
            'position_id' => 5,
        ]);
        DB::table('users')->where('employee_id_number', '2019111402')->update([
            'position_id' => 6,
        ]);
        DB::table('users')->where('employee_id_number', '2019111403')->update([
            'position_id' => 7,
        ]);
        DB::table('users')->where('employee_id_number', '2019111404')->update([
            'position_id' => 8,
        ]);
        DB::table('users')->where('employee_id_number', '2019111405')->update([
            'position_id' => 9,
        ]);
        DB::table('users')->where('employee_id_number', '2022120101')->update([
            'position_id' => 10,
        ]);
        DB::table('users')->where('employee_id_number', '2019111406')->update([
            'position_id' => 11,
        ]);
        DB::table('users')->where('employee_id_number', '2019111407')->update([
            'position_id' => 12,
        ]);
        DB::table('users')->where('employee_id_number', '2022120102')->update([
            'position_id' => 13,
        ]);
        DB::table('users')->where('employee_id_number', '2020123101')->update([
            'position_id' => 14,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122002', '2020012801'])->update([
            'position_id' => 15,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122003', '2019122008'])->update([
            'position_id' => 16,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122005', '2022120103'])->update([
            'position_id' => 17,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122007', '2020040701'])->update([
            'position_id' => 18,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2020010801', '2022120104'])->update([
            'position_id' => 19,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2022120105', '2022120106'])->update([
            'position_id' => 20,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122006', '2019110109'])->update([
            'position_id' => 21,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019122004', '2019122001'])->update([
            'position_id' => 22,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198008042010012004', '2020090701'])->update([
            'position_id' => 23,
        ]);
        DB::table('users')->where('employee_id_number', '2019110110', '2022120107')->update([
            'position_id' => 24,
        ]);
        DB::table('users')->where('employee_id_number', '2022120107')->update([
            'position_id' => 25,
        ]);
        DB::table('users')->where('employee_id_number', '2020090301')->update([
            'position_id' => 26,
        ]);
        DB::table('users')->where('employee_id_number', '2020090702')->update([
            'position_id' => 27,
        ]);
        DB::table('users')->where('employee_id_number', '2020090703')->update([
            'position_id' => 28,
        ]);
        DB::table('users')->where('employee_id_number', '2022120110')->update([
            'position_id' => 30,
        ]);
        DB::table('users')->where('employee_id_number', '2022120109')->update([
            'position_id' => 31,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019102002', '2022021701', '2022112401', '2023080901'])->update([
            'position_id' => 32,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2019102001', '2019102003', '2019102004', '2019102005'])->update([
            'position_id' => 33,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2020040601', '2020040602', '2021093001', '2022011101'])->update([
            'position_id' => 34,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '2019120302',
            '2019120306',
            '2020072101',
            '2019120308',
            '2019120400',
            '2019120305',
            '2019120401',
            '2019120309',
            '2019120304',
            '2019120303',
            '2019120404',
            '2019110106',
        ])->update([
            'position_id' => 35,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['2021012101', '2019120101'])->update([
            'position_id' => 36,
        ]);

        DB::table('users')->whereIn('employee_id_number', ['196505301991031002'])->update([
            'position_id' => 38,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197202161998031005'])->update([
            'position_id' => 39,
        ]);

        DB::table('users')->whereIn('employee_id_number', ['197010271995031001'])->update([
            'position_id' => 40,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196809261994031001'])->update([
            'position_id' => 42,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196605111995031002'])->update([
            'position_id' => 43,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196809271995032001'])->update([
            'position_id' => 44,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198806042010122004'])->update([
            'position_id' => 46,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196911021996032002'])->update([
            'position_id' => 50,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197511122005011001'])->update([
            'position_id' => 54,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197305121998031002'])->update([
            'position_id' => 59,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197405171998032001'])->update([
            'position_id' => 60,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196806171996031001'])->update([
            'position_id' => 61,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197911152005011005'])->update([
            'position_id' => 63,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198512292009012001'])->update([
            'position_id' => 67,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197108091998032002'])->update([
            'position_id' => 70,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196901311997101001'])->update([
            'position_id' => 75,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198206302005011007'])->update([
            'position_id' => 76,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198210182006041002'])->update([
            'position_id' => 77,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197112211997031002'])->update([
            'position_id' => 78,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197405011994032001'])->update([
            'position_id' => 80,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198112282008012007'])->update([
            'position_id' => 85,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198212132008012005'])->update([
            'position_id' => 90,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198408072008011006'])->update([
            'position_id' => 94,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197802202002121001'])->update([
            'position_id' => 97,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196606021992031004'])->update([
            'position_id' => 98,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196909081990031001'])->update([
            'position_id' => 99,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197404071999031001'])->update([
            'position_id' => 100,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196407261986031001'])->update([
            'position_id' => 101,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197501272005012003'])->update([
            'position_id' => 105,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197911142005012002'])->update([
            'position_id' => 106,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197901032005012001'])->update([
            'position_id' => 107,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198312012010121003'])->update([
            'position_id' => 108,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198401232008012004'])->update([
            'position_id' => 109,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197109082005011008'])->update([
            'position_id' => 110,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196611062007011002',
            '199106142018012001',
            '199705162019022001',
            '108308',
            '21130217090494',
            '21150230590695',
            '542613',
            '97030748',
            '00010015',
            '98010487',
            '01050182',
        ])->update([
            'position_id' => 111,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198711252015031001',
            '199404062019021001',
            '199506052020122018',
        ])->update([
            'position_id' => 112,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198807152018011001',
            '199606032018012001',
            '199408082019021001',
            '21090271240990',
            '536831',
            '115225',
            '21050102560486',
            '542630',
            '21150240321293',
        ])->update([
            'position_id' => 113,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197106261998031001', '199304052018012003'])->update([
            'position_id' => 114,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197312092007011002'])->update([
            'position_id' => 116,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198502122012122001'])->update([
            'position_id' => 117,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196704241995031001'])->update([
            'position_id' => 118,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197204011996032001'])->update([
            'position_id' => 119,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197004152006041006'])->update([
            'position_id' => 120,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197308152007011003', '197003181998031001', '197211192006041011'])->update([
            'position_id' => 121,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197004212006041001', '197607132005011001'])->update([
            'position_id' => 124,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197801182002122001'])->update([
            'position_id' => 125,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198007052005012001', '199512262018012001'])->update([
            'position_id' => 126,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197006021995032001', '197806172009011004'])->update([
            'position_id' => 127,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198607292009012001'])->update([
            'position_id' => 128,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197506192002121001'])->update([
            'position_id' => 129,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197804082005012001'])->update([
            'position_id' => 130,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199106252015032001'])->update([
            'position_id' => 132,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198209242005011001'])->update([
            'position_id' => 134,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197706122007101007'])->update([
            'position_id' => 135,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199503282019022001'])->update([
            'position_id' => 138,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197911172005011006'])->update([
            'position_id' => 139,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197502091997031001'])->update([
            'position_id' => 140,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197408082007012002'])->update([
            'position_id' => 141,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198407072005012001',
            '197210022005011001',
            '197405142005011001',
            '198108222005012001',
        ])->update([
            'position_id' => 145,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198110162005012002'])->update([
            'position_id' => 146,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197209071994032001',
            '197002051993031004',
            '197808221998032001',
            '196905282007012001',
        ])->update([
            'position_id' => 148,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197111031996032001'])->update([
            'position_id' => 149,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198511192008012003'])->update([
            'position_id' => 151,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197206082005011006', '197807152009011003'])->update([
            'position_id' => 153,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197209141995032001', '196903012007011004'])->update([
            'position_id' => 154,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197504192007011001'])->update([
            'position_id' => 155,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198203282005011001'])->update([
            'position_id' => 157,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196706091988031001'])->update([
            'position_id' => 159,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197211181992082001'])->update([
            'position_id' => 160,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197203271998031001'])->update([
            'position_id' => 161,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198202032005011004'])->update([
            'position_id' => 164,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196804271995031001', '198510312005012002'])->update([
            'position_id' => 166,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199002112015031001'])->update([
            'position_id' => 167,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197601192009011001'])->update([
            'position_id' => 168,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197805202005012026'])->update([
            'position_id' => 169,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198505092009012005'])->update([
            'position_id' => 170,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197203022005011010'])->update([
            'position_id' => 172,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198910212015031001'])->update([
            'position_id' => 174,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196807151990031001', '196710072006041001'])->update([
            'position_id' => 176,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198006302005011006'])->update([
            'position_id' => 178,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198706212015032002'])->update([
            'position_id' => 179,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197206172005011007'])->update([
            'position_id' => 182,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197904042007011002'])->update([
            'position_id' => 183,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196912261998031001', '197205012005011001'])->update([
            'position_id' => 184,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197107052006041009',
            '197004022007011004',
            '196910082009011001',
            '197303262007011003',
            '197104072006041006',
            '197402262007011002',
            '3920722450173',
            '78314',
        ])->update([
            'position_id' => 185,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197205082005011001'])->update([
            'position_id' => 187,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '31940570830974',
            '518595',
            '31940334640871',
            '31940365900873',
            '21040027590683',
            '536843',
            '86082000',
            '21040039060985',
            '21050097950682',
            '3194047850773',
            '110190',
            '529045',
            '538157',
            '21120081020192',
            '131224',
        ])->update([
            'position_id' => 188,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197011111997032001'])->update([
            'position_id' => 189,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197903292005011001'])->update([
            'position_id' => 210,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197407052007011006'])->update([
            'position_id' => 211,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196312121986021001'])->update([
            'position_id' => 190,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198705042009121001'])->update([
            'position_id' => 213,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197901102005012001'])->update([
            'position_id' => 214,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196604171992022001'])->update([
            'position_id' => 215,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197202241993102002'])->update([
            'position_id' => 216,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['197806092005012001', '198110212005012001'])->update([
            'position_id' => 217,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199309042019022001'])->update([
            'position_id' => 218,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['198705162010121004'])->update([
            'position_id' => 219,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199409132017121005'])->update([
            'position_id' => 191,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196605021994032001', '196804141996032001'])->update([
            'position_id' => 192,
        ]);

        //jabatan fungsional
        DB::table('users')->whereIn('employee_id_number', [
            '198109032006042003',
            '197804172005011002',
            '198201122005012001',
            '198406202009011003',
            '198305192009012004',
            '197907212009011002',
            '199010272014022001',
        ])->update([
            'position_id' => 45,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196608191995032001',
            '197912222005012001',
            '197703222002122001',
            '198508292010121002',
            '197206211997031001',
        ])->update([
            'position_id' => 49,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197301291997032001',
            '196908271996032006',
            '197811172008011007',
            '198102262005012009',
            '198102262005012009',
        ])->update([
            'position_id' => 53,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197703262006041001',
            '198010072005012001',
            '198103172008012014',
            '198811052012122001',
            '199104292015032001',
        ])->update([
            'position_id' => 62,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196712261995032001',
            '197009061994032010',
            '197506091996032001',
            '198311272008012006',
            '198003162008012010',
            '198401192008011003',
            '198304042009011003',
            '199510022019022001',
        ])->update([
            'position_id' => 66,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196910221995032001',
            '197004301996031001',
            '197806101998032001',
            '198007142006041005',
            '198308262009012002',
        ])->update([
            'position_id' => 69,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197703172006042002',
        ])->update([
            'position_id' => 71,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196801061988032003',
            '198003122005011001',
            '198503182008011002',
            '198401292008012004',
            '198604292008012002',
            '198505102012121001',
            '198603242006041001',
        ])->update([
            'position_id' => 79,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197412272005011004',
            '197802262005012001',
            '197209261998031002',
            '198501102008012007',
        ])->update([
            'position_id' => 84,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196702071987091001',
            '197106291998031001',
            '197812162008011009',
            '197808052009012001',
            '198002132008012005',
            '198603092012121001',
        ])->update([
            'position_id' => 89,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196805231998031002',
            '196906101996031001',
            '198502072008012002',
            '198104072009012003',
            '199007142015032002',
        ])->update([
            'position_id' => 93,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197412291998031005',
            '197503262006041001',
            '197806102008011014',
            '198005052005012001',
            '196807041989031002',
            '196902051995032002',
            '198102252008012013',
            '199203022014022002',
            '199006262014022002',
            '198409182008012004',
            '198312202008011002',
            '198412282009121001',
            '199011252015032001',
            '199007092018011001',
            '199304022019021001',
            '197101071997031004',
        ])->update([
            'position_id' => 136,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196603181986031001',
            '197408262005011001',
            '198004242008012019',
            '198505232008012001',
            '198410192009122001',
            '196905271997031001',
            '198208262005012001',
            '198009202008012013',
            '198102112009011006',
            '198807212010121001',
            '198604012009012001',
            '198611062009121003',
            '197512032014062001',
            '198707042015031001',
            '199110162015031001',
            '198611172018011001',
        ])->update([
            'position_id' => 142,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197705272006041002',
            '197402052005011002',
            '196704151991031001',
        ])->update([
            'position_id' => 205,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198009302006042005',
            '197609292005012001',
            '198405272008011004',
            '198508132008012001',
            '199205102015032002',
        ])->update([
            'position_id' => 203,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '197302061997031001',
        ])->update([
            'position_id' => 204,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198509302005011002',
            '198906282015032001',
            '197110192006042001',
            '198904032014022001',
            '198005012008012015',
            '199209302015032001',
        ])->update([
            'position_id' => 147,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '199607302020122017',
        ])->update([
            'position_id' => 208,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198605282009122001',
            '198503022009021001',
            '197801282005011001',
            '198106182006041004',
            '198204202005012001',
            '197102022005011001',
        ])->update([
            'position_id' => 206,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196908081995032002',
            '197303172001122001',
            '197410281997031001',
        ])->update([
            'position_id' => 150,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196812141995031001',
            '197209051995111001',
            '197903122008011010',
            '198403032008011005',
            '197406271997032001',
            '198406052010122003',
        ])->update([
            'position_id' => 162,
        ]);
    }
}
