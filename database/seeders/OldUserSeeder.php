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
                  gelar_dpn as title_prefix,
                  nm_pegawai as name,
                  gelar_blk as title_suffix,
                  CONCAT('photo_profile/', id_pegawai, '.jpg') AS photo_profile,
                  no_nik as id_number,
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
                  tmt_golongan as grade_effective_date,
                  tmt_jabatan as position_effective_date,
                  '1' as organization_id,
                  no_karpeg as employee_id_card_number,
                  no_karisu as wife_id_card_number,
                  no_karisu as husband_id_card_number,
                  npwp as id_tax,
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
                  gelar_dpn AS title_prefix,
                  nm_perbantuan AS name,
                  gelar_blk AS title_suffix,
                  no_ktp AS id_number,
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
                      WHEN email = NULL THEN NULL
                      WHEN email = '' THEN NULL
                      WHEN email = '-' THEN NULL
                      ELSE LOWER(email)
                  END AS email,
                  nm_outsorce AS name,
                  no_ktp AS id_number,
                  id_outsorce AS employee_id_number,
                  tmp_lahir AS place_of_birth,
                  tgl_lahir AS date_of_birth,
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
                  '1' AS organization_id,
                  alamat AS current_address,
                  '3' AS type,
                  CURRENT_TIMESTAMP AS created_at
              FROM
                  simdatuk_dump.tbl_3outsorce
            ";

            $outsource = DB::select($outsource);
            DB::table('users')->insertTs(json_decode(json_encode($outsource), true));
            $this->setPosition();
        }
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
        DB::table('users')->where('employee_id_number', '2019110110')->update([
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
    }
}
