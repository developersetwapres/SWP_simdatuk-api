<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('performance_histories')->delete();
            DB::table('performance_history_users')->delete();

            $performances = "
              SELECT
                periode as name,
                5 as period_month,
                2023 as period_year,
                periode as performance_period
              FROM
                simdatuk_dump.tbl_r_skp
              GROUP BY
                periode
            ";

            $performances = DB::select($performances);
            DB::table('performance_histories')->insertTs(json_decode(json_encode($performances), true));

            $performances = "
              SELECT
                db_baru_users.id as user_id,
                db_baru_performances.id as performance_history_id,
                db_lama_skp.nilai_prestasi as work_performance_score
              FROM
                simdatuk_dump.tbl_r_skp as db_lama_skp
              JOIN
                simdatuk.users as db_baru_users
              ON
                db_lama_skp.id_pegawai = db_baru_users.employee_id_number
              JOIN
                simdatuk.performance_histories as db_baru_performances
              ON
                db_lama_skp.periode = db_baru_performances.name
            ";

            $performances = DB::select($performances);
            foreach ($performances as $performance) {
                // Remove non-numeric characters except commas and dots (assuming decimal format is comma-separated)
                $numericString = preg_replace("/[^0-9,.]/", "", $performance->work_performance_score);

                // Replace comma with dot to standardize decimal format (if needed)
                $numericString = str_replace(",", ".", $numericString);

                $performance->work_performance_score = floatval($numericString);

                if ($performance->work_performance_score >= 51 && $performance->work_performance_score < 61) {
                    $performance->description = 2;
                } else if ($performance->work_performance_score >= 61 && $performance->work_performance_score < 76) {
                    $performance->description = 3;
                } else if ($performance->work_performance_score >= 76 && $performance->work_performance_score < 91) {
                    $performance->description = 4;
                } else if ($performance->work_performance_score >= 91) {
                    $performance->description = 5;
                } else {
                    $performance->description = 1;
                }
            }
            DB::table('performance_history_users')->insertTs(json_decode(json_encode($performances), true));
        }
    }
}
