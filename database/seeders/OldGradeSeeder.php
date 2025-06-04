<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('grade_histories')->delete();
            DB::table('grade_history_users')->delete();

            $grades = "
                SELECT
                    YEAR(db_lama_golongan.tmt_golongan) AS year,
                    MONTH(db_lama_golongan.tmt_golongan) AS month,
                    COUNT(db_lama_golongan.id) AS count
                FROM
                    simdatuk_dump.tbl_r_golongan as db_lama_golongan
                JOIN
                    simdatuk.users as db_baru_users
                ON
                    db_lama_golongan.id_pegawai = db_baru_users.employee_id_number
                GROUP BY
                    YEAR(db_lama_golongan.tmt_golongan),
                    MONTH(db_lama_golongan.tmt_golongan)
                ORDER BY
                    year,
                    month
            ";
            $grades = DB::select($grades);

            foreach ($grades as $item) {
                $month = (is_null($item->month)) ? 5 : $item->month;
                $year = (is_null($item->year)) ? 2024 : $item->year;
                $gradeId = DB::table('grade_histories')->insertGetIdTs([
                    'name' => 'Perubahan Golongan ' . $this->getIndonesianMonthName($item->month) . ' ' . $item->year,
                    'period_month' => $month,
                    'period_year' => $year,
                ]);

                $userGrade = "
                  SELECT
                      db_baru_users.id as user_id,
                      '$gradeId' as grade_history_id,
                      db_baru_grades.id as grade_id,
                      db_lama_golongan.tmt_golongan as effective_date,
                      db_lama_golongan.sk_golongan as decree_name,
                      db_baru_decrees.id as type_of_decree,
                      db_lama_golongan.no_sk_golongan as decree_number,
                      db_lama_golongan.tgl_sk_golongan as decree_date,
                      db_lama_golongan.ket_golongan as description,
                      CASE
                        WHEN db_lama_golongan.stat_golongan = 'aktif' THEN TRUE
                        ELSE FALSE
                      END AS status,
                      CURRENT_TIMESTAMP AS created_at
                  FROM
                      simdatuk_dump.tbl_r_golongan as db_lama_golongan
                  JOIN
                      simdatuk.users as db_baru_users
                  ON
                      db_lama_golongan.id_pegawai = db_baru_users.employee_id_number
                  LEFT JOIN
                      simdatuk_dump.tbl_mst_golongan as db_lama_mst_golongan
                  ON
                      db_lama_golongan.id_golongan = db_lama_mst_golongan.id_golongan
                  LEFT JOIN
                      simdatuk.grades as db_baru_grades
                  ON
                      db_lama_mst_golongan.pangkat = db_baru_grades.name
                  LEFT JOIN
                      simdatuk.decrees as db_baru_decrees
                  ON
                      db_lama_golongan.jns_sk_golongan = db_baru_decrees.acronym
                ";

                if ($month == 5 && $year == 2024) {
                    $condition = "
                      WHERE db_lama_golongan.tmt_golongan IS NULL
                    ";
                } else {
                    $condition = "
                      WHERE
                        YEAR(db_lama_golongan.tmt_golongan) = '$year'
                      AND
                        MONTH(db_lama_golongan.tmt_golongan) = '$month'
                    ";
                }

                $userGrade = DB::select($userGrade . $condition);
                DB::table('grade_history_users')->insertTs(json_decode(json_encode($userGrade), true));
            }
        }
    }

    private static function getIndonesianMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$monthNumber] ?? 'Mei';
    }
}
