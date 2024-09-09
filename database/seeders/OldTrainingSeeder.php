<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            DB::table('training_histories')->delete();
            DB::table('training_history_users')->delete();
            $this->getStrukturalAndFungsional(1);
            $this->getStrukturalAndFungsional(2);
            $this->getTeknis();
        }
    }

    private function getStrukturalAndFungsional($type)
    {
        $sql = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        DB::select($sql);

        $userTraining = "
            SELECT
                db_baru_users.id as user_id,
                CASE
                    WHEN db_lama_training.nm_diklat = '' THEN db_lama_training.keterangan
                    WHEN db_lama_training.nm_diklat IS NULL THEN db_lama_training.keterangan
                    ELSE db_lama_training.nm_diklat
                END as name,
                db_lama_training.jenjang as level,
                CASE
                    WHEN db_lama_training.thn_jenjang = '' THEN NULL
                    ELSE db_lama_training.thn_jenjang
                END AS period_year,
                db_lama_training.penyelenggara as organizer
            FROM
                simdatuk_dump.tbl_r_dik_str_fung as db_lama_training
            JOIN
                simdatuk.users as db_baru_users
            ON
                db_lama_training.id_pegawai = db_baru_users.employee_id_number
            WHERE
                ($type = 1 AND db_lama_training.jenjang NOT LIKE '%Fungsional%')
                OR ($type <> 1 AND db_lama_training.jenjang LIKE '%Fungsional%')
            GROUP BY
                user_id, name, level, period_year, organizer
            ORDER BY
                period_year desc
        ";
        $userTraining = DB::select($userTraining);
        $userTraining = json_decode(json_encode($userTraining), true);

        $groupedData = [];

        foreach ($userTraining as $item) {
            $groupKey = ($item['name'] ?? 'null') . '|' . ($item['level'] ?? 'null') . '|' . ($item['period_year'] ?? 'null') . '|' . ($item['organizer'] ?? 'null');
            if (!isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [];
            }
            $groupedData[$groupKey][] = $item;
        }

        // Output the grouped data
        foreach ($groupedData as $key => $group) {
            $item = explode("|", $key);
            // insert jenjang
            if ($item[1] == 'null') {
                $trainingLevelId = 6;   // Pelatihan Lainnya refers to training_levels
            } elseif (ucfirst($item[1]) == 'Fungsional') {
                $trainingLevelId = 7;
            } else {
                $checkLevel = DB::table('training_levels')->where('level_name', 'LIKE', DB::raw('%'.$item[1].'%'));
                if ($checkLevel->count() > 0) {
                    $trainingLevelId = $checkLevel->first();
                    $trainingLevelId = $trainingLevelId->id;
                } else {
                    $trainingLevelId = DB::table('training_levels');
                    $trainingLevelId = $trainingLevelId->insertGetIdTs([
                        'level_name' => $item[1],
                    ]);
                }
            }
            // insert riwayat pelatihan
            $trainingId = DB::table('training_histories');
            $trainingId = $trainingId->insertGetIdTs([
                'name'        => ($item[0] == 'null') ? null : $item[0],
                'level'       => ($trainingLevelId == null) ? null : $trainingLevelId,
                'period_year' => ($item[2] == 'null') ? null : $item[2],
                'organizer'   => ($item[3] == 'null') ? null : $item[3],
                'type'        => $type,
            ]);
            foreach ($group as $item) {
                $user = DB::table('training_history_users');
                $user = $user->insertTs([
                    'user_id' => $item['user_id'],
                    'training_history_id' => $trainingId,
                ]);
            }
        }
    }

    private function getTeknis()
    {
        $trainings = "
            SELECT
                id_dik_teknis as id,
                CASE
                    WHEN db_lama_teknis.tahun = '' THEN NULL
                    WHEN db_lama_teknis.tahun > 2900 THEN NULL
                    ELSE db_lama_teknis.tahun
                END AS period_year,
                db_lama_teknis.nm_dik_teknis as name,
                db_lama_teknis.mulai as start_date,
                CASE
                    WHEN db_lama_teknis.durasi = '' THEN NULL
                    ELSE db_lama_teknis.durasi
                END AS duration,
                db_lama_teknis.nm_evorg as organizer
            FROM
                simdatuk_dump.tbl_dik_teknis as db_lama_teknis
        ";

        $trainings = DB::select($trainings);
        foreach ($trainings as $item) {
            // Insert to training
            $trainingId = DB::table('training_histories');
            $trainingId = $trainingId->insertGetIdTs([
                'name' => $item->name,
                'period_year' => $item->period_year,
                'start_date' => $item->start_date,
                'duration' => $item->duration,
                'organizer' => $item->organizer,
                'type' => 3,
            ]);

            // Insert to user training
            $userTraining = "
                SELECT
                    db_baru_users.id as user_id,
                    $trainingId as training_history_id,
                    CURRENT_TIMESTAMP AS created_at
                FROM
                    simdatuk_dump.tbl_r_dik_teknis as db_lama_teknis
                JOIN
                    simdatuk.users as db_baru_users
                ON
                    db_lama_teknis.id_pegawai = db_baru_users.employee_id_number
                WHERE
                    db_lama_teknis.id_r_dik_teknis = '$item->id'
            ";
            $userTraining = DB::select($userTraining);
            if (count($userTraining) > 0) {
                DB::table('training_history_users')->insertTs(json_decode(json_encode($userTraining), true));
            }
        }
    }
}
