<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldStrukturalTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strukturalTraining = "
            SELECT * FROM (
            (SELECT tbl_lama_trainings.jenjang as name, '1' as period_month, 
            tbl_lama_trainings.thn_jenjang as period_year, 
            '1' as type, tbl_lama_trainings.penyelenggara as organizer 
            FROM simdatuk_dump.tbl_r_dik_str_fung as tbl_lama_trainings 
            WHERE tbl_lama_trainings.jenjang = 'Diklat PIM Tk.I' 
            GROUP BY tbl_lama_trainings.thn_jenjang, tbl_lama_trainings.jenjang, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.thn_jenjang asc) 
            UNION 
            (SELECT tbl_lama_trainings.jenjang as name, '1' as period_month, 
            tbl_lama_trainings.thn_jenjang as period_year, 
            '1' as type, tbl_lama_trainings.penyelenggara as organizer 
            FROM simdatuk_dump.tbl_r_dik_str_fung as tbl_lama_trainings 
            WHERE tbl_lama_trainings.jenjang = 'Diklat PIM Tk.II' 
            GROUP BY tbl_lama_trainings.thn_jenjang, tbl_lama_trainings.jenjang, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.thn_jenjang asc) 
            UNION 
            (SELECT tbl_lama_trainings.jenjang as name, '1' as period_month, 
            tbl_lama_trainings.thn_jenjang as period_year, 
            '1' as type, tbl_lama_trainings.penyelenggara as organizer 
            FROM simdatuk_dump.tbl_r_dik_str_fung as tbl_lama_trainings 
            WHERE tbl_lama_trainings.jenjang = 'Diklat PIM Tk.III' 
            GROUP BY tbl_lama_trainings.thn_jenjang, tbl_lama_trainings.jenjang, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.thn_jenjang asc) 
            UNION 
            (SELECT tbl_lama_trainings.jenjang as name, '1' as period_month, 
            tbl_lama_trainings.thn_jenjang as period_year, 
            '1' as type, tbl_lama_trainings.penyelenggara as organizer 
            FROM simdatuk_dump.tbl_r_dik_str_fung as tbl_lama_trainings 
            WHERE tbl_lama_trainings.jenjang = 'Diklat PIM Tk.IV' 
            GROUP BY tbl_lama_trainings.thn_jenjang, tbl_lama_trainings.jenjang, tbl_lama_trainings.penyelenggara 
            ORDER BY tbl_lama_trainings.thn_jenjang asc)
            ) AS old_trainings ORDER BY name
        ";

        $strukturalTraining = DB::select($strukturalTraining);
        DB::table('trainings')->insertTs(json_decode(json_encode($strukturalTraining), true));
    }
}
