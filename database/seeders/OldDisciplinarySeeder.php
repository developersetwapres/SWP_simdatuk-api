<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldDisciplinarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('disciplinary_histories')->delete();
            DB::table('disciplinary_history_users')->delete();

            $disciplinaryId = DB::table('disciplinary_histories')->insertGetIdTs([
                'name' => 'Hukuman Disiplin 2023',
                'period_month' => 1,
                'period_year' => 2023,
            ]);

            $disciplinaries = "
              SELECT
                  db_baru_users.id as user_id,
                  CURRENT_TIMESTAMP AS created_at,
                  '$disciplinaryId' as disciplinary_history_id,
                  db_lama_hukdis.golongan as grade,
                  db_lama_hukdis.jabatan as position,
                  CASE
                      WHEN db_lama_hukdis.id_hukdis = 'A1' THEN 1
                      WHEN db_lama_hukdis.id_hukdis = 'A2' THEN 2
                      WHEN db_lama_hukdis.id_hukdis = 'A3' THEN 3
                      WHEN db_lama_hukdis.id_hukdis = 'B1' THEN 4
                      WHEN db_lama_hukdis.id_hukdis = 'B2' THEN 5
                      WHEN db_lama_hukdis.id_hukdis = 'B3' THEN 6
                      WHEN db_lama_hukdis.id_hukdis = 'C1' THEN 7
                      WHEN db_lama_hukdis.id_hukdis = 'C2' THEN 8
                      WHEN db_lama_hukdis.id_hukdis = 'C3' THEN 9
                      WHEN db_lama_hukdis.id_hukdis = 'C4' THEN 10
                      WHEN db_lama_hukdis.id_hukdis = 'C5' THEN 11
                      ELSE NULL
                  END AS disciplinary_id,
                  db_lama_hukdis.no_sk_hukdis as decree_number,
                  db_lama_hukdis.tgl_sk_hukdis as date_of_decree,
                  db_lama_hukdis.mulai as start_date,
                  db_lama_hukdis.sampai as end_date,
                  db_lama_hukdis.pjb_berwenang as authorizing_officer,
                  db_lama_hukdis.nm_pjb_berwenang as name_of_authorizing_officer
              FROM
                  simdatuk_dump.tbl_r_hukdis as db_lama_hukdis
              JOIN
                  simdatuk.users as db_baru_users
              ON
                  db_lama_hukdis.id_pegawai = db_baru_users.employee_id_number
            ";

            $disciplinaries = DB::select($disciplinaries);
            DB::table('disciplinary_history_users')->insertTs(json_decode(json_encode($disciplinaries), true));
        }
    }
}
