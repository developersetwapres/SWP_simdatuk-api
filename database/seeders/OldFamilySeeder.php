<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('user_families')->delete();
            $families = "
              SELECT
                db_baru_users.id AS user_id,
                db_lama_keluarga.no_kk AS card_number,
                db_lama_keluarga.nm_angkel AS name,
                db_lama_keluarga.no_nik AS id_number,
                CASE
                  WHEN db_lama_keluarga.kelamin = 'Perempuan' THEN 0
                  WHEN db_lama_keluarga.kelamin = 'Laki-laki' THEN 1
                  ELSE NULL
                END AS gender,
                CASE
                  WHEN db_lama_keluarga.agama = 'Islam' THEN 1
                  WHEN db_lama_keluarga.agama = 'Protestan' THEN 2
                  WHEN db_lama_keluarga.agama = 'Katholik' THEN 3
                  WHEN db_lama_keluarga.agama = 'Hindu' THEN 4
                  ELSE NULL
                END AS religion,
                db_lama_keluarga.tmpt_lahir AS place_of_birth,
                db_lama_keluarga.tgl_lahir AS date_of_birth,
                db_lama_keluarga.bapak AS name_of_father,
                db_lama_keluarga.ibu AS name_of_mother,
                CASE
                  WHEN db_lama_keluarga.hub_kel = 'Suami' THEN 2
                  WHEN db_lama_keluarga.hub_kel = 'Bapak' THEN 7
                  WHEN db_lama_keluarga.hub_kel = 'Ibu' THEN 7
                  WHEN db_lama_keluarga.hub_kel = 'Anak Kandung' THEN 4
                  WHEN db_lama_keluarga.hub_kel = 'Isteri' THEN 3
                  WHEN db_lama_keluarga.hub_kel = 'Bapak Mrt' THEN 8
                  WHEN db_lama_keluarga.hub_kel = 'Ibu Mrt' THEN 8
                  ELSE NULL
                END AS relationship_status,
                CASE
                  WHEN db_lama_keluarga.pddk = 'Strata II' THEN 9
                  WHEN db_lama_keluarga.pddk = 'SLTA/Sederajat' THEN 5
                  WHEN db_lama_keluarga.pddk = 'Diploma IV/Strata I' THEN 8
                  WHEN db_lama_keluarga.pddk = 'Akademi/D3/S.Muda' THEN 7
                  WHEN db_lama_keluarga.pddk = 'SD/Sederajat' THEN 2
                  WHEN db_lama_keluarga.pddk = 'SLTP/Sederajat' THEN 4
                  WHEN db_lama_keluarga.pddk = 'Mahasiswa' THEN 8
                  WHEN db_lama_keluarga.pddk = 'Diploma I/II' THEN 6
                  WHEN db_lama_keluarga.pddk = 'Strata III' THEN 10
                  WHEN db_lama_keluarga.pddk = 'TK' THEN 2
                  WHEN db_lama_keluarga.pddk = 'Belum Sekolah' THEN 1
                  WHEN db_lama_keluarga.pddk = 'Lulus SD' THEN 3
                  ELSE NULL
                END AS education,
                db_lama_keluarga.jns_pekerjaan AS occupation,
                db_lama_keluarga.ket_pekerjaan AS occupation_description,
                CASE
                  WHEN db_lama_keluarga.status_nkh = 'Kawin' THEN 2
                  WHEN db_lama_keluarga.status_nkh = 'Belum Kawin' THEN 1
                  WHEN db_lama_keluarga.status_nkh = 'Cerai Hidup' THEN 3
                  ELSE NULL
                END AS marital_status,
                db_lama_keluarga.urut_kel AS sequence_number,
                CURRENT_TIMESTAMP AS created_at
              FROM
                simdatuk_dump.tbl_r_keluarga_pswp AS db_lama_keluarga
              JOIN
                simdatuk.users AS db_baru_users
              ON
                db_lama_keluarga.id_pegawai = db_baru_users.employee_id_number
            ";
            $families = DB::select($families);
            DB::table('user_families')->insertTs(json_decode(json_encode($families), true));
        }
    }
}
