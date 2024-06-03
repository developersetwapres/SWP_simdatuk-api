<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('user_educations')->delete();

            $educations = "
              SELECT
                db_baru_users.id as user_id,
                CASE
                  WHEN db_lama_pddk.jenjang = 'SD/Sederajat' THEN 1
                  WHEN db_lama_pddk.jenjang = 'SLTP/Sederajat' THEN 2
                  WHEN db_lama_pddk.jenjang = 'SLTA/Sederajat' THEN 3
                  WHEN db_lama_pddk.jenjang = 'Diploma I/II' THEN 4
                  WHEN db_lama_pddk.jenjang = 'Akademik/D3/S.Muda' THEN 5
                  WHEN db_lama_pddk.jenjang = 'Diploma IV/Strata I' THEN 6
                  WHEN db_lama_pddk.jenjang = 'Strata II' THEN 7
                  WHEN db_lama_pddk.jenjang = 'Strata III' THEN 8
                  ELSE NULL
                END AS level,
                db_lama_pddk.ket_sekolah as name,
                db_lama_pddk.fakultas as faculty,
                db_lama_pddk.jurusan as major,
                '1' as status,
                db_lama_pddk.thn_lulus as year_of_graduation,
                db_lama_pddk.ket_sekolah as description,
                CURRENT_TIMESTAMP AS created_at
              FROM
                simdatuk_dump.tbl_r_pddkformal as db_lama_pddk
              JOIN
                simdatuk.users as db_baru_users
              ON
                db_lama_pddk.id_pegawai = db_baru_users.employee_id_number
            ";

            $educations = DB::select($educations);
            DB::table('user_educations')->insertTs(json_decode(json_encode($educations), true));
        }
    }
}
