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
            '01050182'
        ])->update([
            'position_id' => 111,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '198711252015031001',
            '199404062019021001',
            '199506052020122018'
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
            '21150240321293'
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
        DB::table('users')->whereIn('employee_id_number', ['197206172005011007'])->update([
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
        DB::table('users')->whereIn('employee_id_number', ['197011111997032001', '197903292005011001', '197407052007011006'])->update([
            'position_id' => 189,
        ]);
        DB::table('users')->whereIn('employee_id_number', [
            '196312121986021001',
            '198705042009121001',
            '197901102005012001',
            '196604171992022001',
            '197202241993102002',
            '197806092005012001',
            '198110212005012001',
            '199309042019022001',
            '198705162010121004',
        ])->update([
            'position_id' => 190,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['199409132017121005'])->update([
            'position_id' => 191,
        ]);
        DB::table('users')->whereIn('employee_id_number', ['196605021994032001', '196804141996032001'])->update([
            'position_id' => 192,
        ]);
    }
}
