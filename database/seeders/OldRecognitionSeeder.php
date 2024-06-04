<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldRecognitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('recognitions')->delete();
            DB::table('user_recognitions')->delete();

            $recognitions = "
                SELECT
                    db_lama_bintang.id_sk_bintang AS id,
                    YEAR(db_lama_bintang.tgl_sk) AS year,
                    MONTH(db_lama_bintang.tgl_sk) AS month,
                    db_lama_mstbintang.nm_bintang as name,
                    db_lama_mstbintang.ket_bintang as description,
                    db_baru_decrees.id as type_of_decree,
                    db_lama_bintang.tgl_sk as decree_date,
                    db_lama_bintang.tahun_sk as decree_year,
                    db_lama_bintang.tgl_sk as date_of_receipt
                FROM
                    simdatuk_dump.tbl_sk_bintang as db_lama_bintang
                JOIN
                    simdatuk_dump.tbl_r_bintang as db_lama_rbintang
                ON
                    db_lama_bintang.id_sk_bintang = db_lama_rbintang.id_r_bintang
                JOIN
                    simdatuk_dump.tbl_mst_bintang as db_lama_mstbintang
                ON
                    db_lama_bintang.id_bintang = db_lama_mstbintang.id_bintang
                LEFT JOIN
                    simdatuk.decrees as db_baru_decrees
                ON
                    db_lama_bintang.jns_sk = db_baru_decrees.acronym
                WHERE
                    db_lama_bintang.tgl_sk IS NOT NULL
                ORDER BY
                    year,
                    month
            ";

            $recognitions = DB::select($recognitions);

            foreach ($recognitions as $item) {
                $recognitionId = DB::table('recognitions')->insertGetIdTs([
                    'id' => $item->id,
                    'period_month' => $item->month,
                    'period_year' => $item->year,
                    'name' => 'Penghargaan ' . $item->name . ' ' . $this->getIndonesianMonthName($item->month) . ' ' . $item->year,
                    'description' => $item->description,
                    'type_of_decree' => $item->type_of_decree,
                    'decree_date' => $item->decree_date,
                    'decree_year' => $item->decree_year,
                    'date_of_receipt' => $item->date_of_receipt,
                ]);

                $userRecognition = "
                  SELECT
                      db_baru_users.id as user_id,
                      '$recognitionId' as recognition_id,
                      CURRENT_TIMESTAMP AS created_at
                  FROM
                      simdatuk_dump.tbl_r_bintang as db_lama_bintang
                  JOIN
                      simdatuk.users as db_baru_users
                  ON
                      db_lama_bintang.id_pegawai = db_baru_users.employee_id_number
                  WHERE
                      db_lama_bintang.id_sk_bintang = '$recognitionId'
                ";

                $userRecognition = DB::select($userRecognition);
                DB::table('user_recognitions')->insertTs(json_decode(json_encode($userRecognition), true));
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
