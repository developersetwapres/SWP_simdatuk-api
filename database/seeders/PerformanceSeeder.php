<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('performances')->delete();
        DB::table('user_performances')->delete();
        if (config('app.env') == 'production') {
            $this->realDatabese();
        } else {
            $this->dummyDatabase();
        }
    }

    /**
     * Generate with dummy data
     *
     * @return void
     */
    private function dummyDatabase()
    {
    }

    /**
     * Generate data from real database
     *
     * @return void
     */
    private function realDatabese()
    {
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
        DB::table('performances')->insertTs(json_decode(json_encode($performances), true));

        $performances = "
          SELECT
            db_baru_users.id as user_id,
            db_baru_performances.id as performance_id,
            db_lama_skp.nilai_prestasi as work_performance_score
          FROM
            simdatuk_dump.tbl_r_skp as db_lama_skp
          JOIN
            simdatuk.users as db_baru_users
          ON
            db_lama_skp.id_pegawai = db_baru_users.employee_id_number
          JOIN
            simdatuk.performances as db_baru_performances
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
        }
        DB::table('user_performances')->insertTs(json_decode(json_encode($performances), true));
    }
}
