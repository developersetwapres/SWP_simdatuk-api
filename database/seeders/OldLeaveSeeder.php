<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('user_leaves')->delete();
            $leaves = "
                SELECT
                    db_baru_users.id as user_id,
                    db_lama_cuti.mulai as start_date,
                    db_lama_cuti.sampai as end_date,
                    CASE
                        WHEN db_lama_cuti.jns_cuti = 'CBR' THEN 3
                        WHEN db_lama_cuti.jns_cuti = 'CBL' THEN 4
                        WHEN db_lama_cuti.jns_cuti = 'CLTN' THEN 1
                        WHEN db_lama_cuti.jns_cuti = 'CSK' THEN 2
                        WHEN db_lama_cuti.jns_cuti = 'TBL' THEN 5
                        ELSE NULL
                    END as type,
                    db_lama_cuti.no_sk_cuti as number,
                    db_lama_cuti.keterangan as description,
                    CURRENT_TIMESTAMP AS created_at
                FROM
                    simdatuk_dump.tbl_r_cuti as db_lama_cuti
                JOIN
                    simdatuk.users as db_baru_users
                ON
                  db_lama_cuti.id_pegawai = db_baru_users.employee_id_number
            ";
            $leaves = DB::select($leaves);
            DB::table('user_leaves')->insertTs(json_decode(json_encode($leaves), true));
        }
    }
}
