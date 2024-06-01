<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('disciplinaries')->delete();
        $disciplinaryTypes = [
            [
                "id" => 1,
                "name" => "Teguran Lisan",
                "description" => "Hukuman Disiplin Tingkat Ringan 1",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 2,
            ],
            [
                "id" => 2,
                "name" => "Teguran Tertulis",
                "description" => "Hukuman Disiplin Tingkat Ringan 2",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 3,
            ],
            [
                "id" => 3,
                "name" => "Pernyataan Tidak Puas Secara Tertulis",
                "description" => "Hukuman Disiplin Tingkat Ringan 3",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 6,
            ],
            [
                "id" => 4,
                "name" => "Penundaan Kenaikan Gaji Berkala Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 1",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 6,
            ],
            [
                "id" => 5,
                "name" => "Penundaan Kenaikan Pangkat Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 2",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 9,
            ],
            [
                "id" => 6,
                "name" => "Penurunan Pangkat Setingkat Lebih Rendah Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 3",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 12,
            ],
            [
                "id" => 7,
                "name" => "Penurunan Pangkat Setingkat Lebih Rendah Selama 3 (Tiga) Tahun",
                "description" => "Hukuman Disiplin Tingkat Berat 1",
                "performance_allowance_deduction" => "0.75",
                "performance_allowance_duration" => 12,
            ],
            [
                "id" => 8,
                "name" => "Pemindahan Dalam Rangka Penurunan Jabatan Setingkat Lebih Rendah",
                "description" => "Hukuman Disiplin Tingkat Berat 2",
                "performance_allowance_deduction" => "0.9",
                "performance_allowance_duration" => 12,
            ],
            [
                "id" => 9,
                "name" => "Pembebasan Dari Jabatan",
                "description" => "Hukuman Disiplin Tingkat Berat 3",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
            [
                "id" => 10,
                "name" => "Pemberhentian Dengan Hormat Tidak Atas Permintaan Sendiri Sebagai PNS",
                "description" => "Hukuman Disiplin Tingkat Berat 4",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
            [
                "id" => 11,
                "name" => "Pemberhentian Tidak Dengan Hormat Sebagai PNS",
                "description" => "Hukuman Disiplin Tingkat Berat 5",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
        ];
        DB::table('disciplinaries')->insertTs($disciplinaryTypes);

        if (config('app.env') == 'production') {
            $this->realDatabese();
        }
    }

    /**
     * Generate data from real database
     *
     * @return void
     */
    private function realDatabese()
    {
        DB::table('disciplinary_histories')->delete();
        DB::table('disciplinary_history_users')->delete();
        $disciplinaryId = DB::table('disciplinary_histories')->insertGetIdTs([
            'name' => 'Hukuman Disiplin 2023',
            'period_month' => 1,
            'period_year' => 2023,
        ]);
        $disciplinaries = "
            SELECT
                db_baru_users.id as user_id,
                CURRENT_TIMESTAMP AS created_at,
                '$disciplinaryId' as disciplinary_history_id,
                db_lama_hukdis.golongan as grade,
                db_lama_hukdis.jabatan as position,
                CASE
                    WHEN db_lama_hukdis.id_hukdis = 'A1' THEN 1
                    WHEN db_lama_hukdis.id_hukdis = 'A2' THEN 2
                    WHEN db_lama_hukdis.id_hukdis = 'A3' THEN 3
                    WHEN db_lama_hukdis.id_hukdis = 'B1' THEN 4
                    WHEN db_lama_hukdis.id_hukdis = 'B2' THEN 5
                    WHEN db_lama_hukdis.id_hukdis = 'B3' THEN 6
                    WHEN db_lama_hukdis.id_hukdis = 'C1' THEN 7
                    WHEN db_lama_hukdis.id_hukdis = 'C2' THEN 8
                    WHEN db_lama_hukdis.id_hukdis = 'C3' THEN 9
                    WHEN db_lama_hukdis.id_hukdis = 'C4' THEN 10
                    WHEN db_lama_hukdis.id_hukdis = 'C5' THEN 11
                    ELSE NULL
                END AS disciplinary_id,
                db_lama_hukdis.no_sk_hukdis as decree_number,
                db_lama_hukdis.tgl_sk_hukdis as date_of_decree,
                db_lama_hukdis.mulai as start_date,
                db_lama_hukdis.sampai as end_date,
                db_lama_hukdis.pjb_berwenang as authorizing_officer,
                db_lama_hukdis.nm_pjb_berwenang as name_of_authorizing_officer
            FROM
                simdatuk_dump.tbl_r_hukdis as db_lama_hukdis
            JOIN
                simdatuk.users as db_baru_users
            ON
                db_lama_hukdis.id_pegawai = db_baru_users.employee_id_number
        ";
        $disciplinaries = DB::select($disciplinaries);
        DB::table('disciplinary_history_users')->insertTs(json_decode(json_encode($disciplinaries), true));
    }
}
