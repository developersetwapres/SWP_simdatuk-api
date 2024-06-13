<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldTeknisTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknisTraining = "
            SELECT tbl_lama_trainings.nm_dik_teknis AS name, '1' AS period_month,
            CASE WHEN regexp_instr('^[0-9]{10}$', TRIM(tbl_lama_trainings.tahun)) = 1 THEN 2024 
                ELSE
                CASE WHEN tbl_lama_trainings.tahun > 2024 THEN 2024 ELSE tbl_lama_trainings.tahun END
            END as period_year,
            '3' as type, tbl_lama_trainings.penyelenggara AS organizer 
            FROM simdatuk_dump.tbl_dik_teknis AS tbl_lama_trainings 
            WHERE tbl_lama_trainings.nm_dik_teknis IS NOT NULL
            GROUP BY tbl_lama_trainings.tahun, tbl_lama_trainings.nm_dik_teknis, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.tahun ASC
        ";

        $teknisTraining = DB::select($teknisTraining);
        DB::table('trainings')->insertTs(json_decode(json_encode($teknisTraining), true));
    }
}
