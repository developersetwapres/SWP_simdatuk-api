<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldFungsionalTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fungsionalTraining = "INSERT INTO trainings (`name`, period_month, period_year, `type`, organizer, created_at)
            SELECT tbl_lama_trainings.nm_diklat AS name, '1' as period_month, 
            CASE WHEN regexp_instr('^[0-9]{10}$', TRIM(tbl_lama_trainings.thn_jenjang)) = 1 THEN NULL 
                ELSE tbl_lama_trainings.thn_jenjang 
            END as period_year, 
            '2' as type, tbl_lama_trainings.penyelenggara as organizer,
            NOW() as created_at 
            FROM simdatuk_dump.tbl_r_dik_str_fung as tbl_lama_trainings 
            WHERE tbl_lama_trainings.jenjang = 'Fungsional' 
            GROUP BY tbl_lama_trainings.thn_jenjang, tbl_lama_trainings.nm_diklat, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.thn_jenjang ASC             
        ";
        DB::statement($fungsionalTraining);
    }
}
