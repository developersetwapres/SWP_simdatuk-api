<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('position_histories')->delete();
            DB::table('position_history_users')->delete();

            $positions = "
                SELECT
                    YEAR(db_lama_jabatan.tmt_menjabat) AS year,
                    MONTH(db_lama_jabatan.tmt_menjabat) AS month,
                    COUNT(db_lama_jabatan.id) AS count
                FROM
                    simdatuk_dump.tbl_r_jabatan as db_lama_jabatan
                JOIN
                    simdatuk.users as db_baru_users
                ON
                    db_lama_jabatan.id_pegawai = db_baru_users.employee_id_number
                GROUP BY
                    YEAR(db_lama_jabatan.tmt_menjabat),
                    MONTH(db_lama_jabatan.tmt_menjabat)
                ORDER BY
                    year,
                    month
            ";

            $positions = DB::select($positions);

            foreach ($positions as $item) {
                $month = (is_null($item->month)) ? 5 : $item->month;
                $year = (is_null($item->year)) ? 2024 : $item->year;
                $positionId = DB::table('position_histories')->insertGetIdTs([
                    'name' => 'Perubahan Jabatan ' . $this->getIndonesianMonthName($item->month) . ' ' . $item->year,
                    'period_month' => $month,
                    'period_year' => $year,
                ]);

                $userPosition = "
                  SELECT
                      db_baru_users.id as user_id,
                      '$positionId' as position_history_id,
                      db_lama_jabatan.nm_jabatan as position,
                      CASE
                        WHEN db_lama_jabatan.ket_eselon = 'Eselon I' THEN 1
                        WHEN db_lama_jabatan.ket_eselon = 'Eselon II' THEN 2
                        WHEN db_lama_jabatan.ket_eselon = 'Eselon III' THEN 3
                        WHEN db_lama_jabatan.ket_eselon = 'Eselon IV' THEN 4
                        WHEN db_lama_jabatan.ket_eselon = 'Pelaksana' THEN 5
                        WHEN db_lama_jabatan.ket_eselon = 'Fungsional' THEN 6
                        WHEN db_lama_jabatan.ket_eselon = 'Staf' THEN 7
                        ELSE NULL
                      END AS echelon,
                      CASE
                        WHEN db_lama_jabatan.ket_jabatan = 'promosi' THEN 1
                        WHEN db_lama_jabatan.ket_jabatan = 'mutasi' THEN 2
                        WHEN db_lama_jabatan.ket_jabatan = 'inpassing' THEN 3
                        ELSE NULL
                      END AS position_status,
                      db_lama_jabatan.tmt_menjabat as effective_date,
                      db_lama_jabatan.sk_menjabat as decree,
                      db_baru_decrees.id as type_of_decree,
                      db_lama_jabatan.no_sk_jb as decree_number,
                      db_lama_jabatan.tgl_sk_jb as decree_date,
                      db_lama_jabatan.tmt_selesai as termination_date,
                      db_lama_jabatan.sk_selesai as termination_decree,
                      db_baru_decrees_sls.id as type_of_termination_decree,
                      db_lama_jabatan.no_sk_sls as termination_decree_number,
                      db_lama_jabatan.tgl_sk_sls as termination_decree_date,
                      CASE
                        WHEN db_lama_jabatan.stat_jab = 'aktif' THEN TRUE
                        ELSE FALSE
                      END AS status,
                      CURRENT_TIMESTAMP AS created_at
                  FROM
                      simdatuk_dump.tbl_r_jabatan as db_lama_jabatan
                  JOIN
                      simdatuk.users as db_baru_users
                  ON
                      db_lama_jabatan.id_pegawai = db_baru_users.employee_id_number
                  LEFT JOIN
                      simdatuk.decrees as db_baru_decrees
                  ON
                      db_lama_jabatan.jns_sk_jb = db_baru_decrees.acronym
                  LEFT JOIN
                      simdatuk.decrees as db_baru_decrees_sls
                  ON
                      db_lama_jabatan.jns_sk_sls = db_baru_decrees_sls.acronym
                ";

                if ($month == 5 && $year == 2024) {
                    $condition = "
                      WHERE db_lama_jabatan.tmt_menjabat IS NULL
                    ";
                } else {
                    $condition = "
                      WHERE
                        YEAR(db_lama_jabatan.tmt_menjabat) = '$year'
                      AND
                        MONTH(db_lama_jabatan.tmt_menjabat) = '$month'
                    ";
                }

                $userPosition = DB::select($userPosition . $condition);
                DB::table('position_history_users')->insertTs(json_decode(json_encode($userPosition), true));
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
