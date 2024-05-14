<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinaryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('disciplinary_types')->delete();
        $disciplinaryTypes = [
            [
                "name" => "Teguran Lisan",
                "description" => "Hukuman Disiplin Tingkat Ringan 1",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 2,
            ],
            [
                "name" => "Teguran Tertulis",
                "description" => "Hukuman Disiplin Tingkat Ringan 2",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 3,
            ],
            [
                "name" => "Pernyataan Tidak Puas Secara Tertulis",
                "description" => "Hukuman Disiplin Tingkat Ringan 3",
                "performance_allowance_deduction" => "0.25",
                "performance_allowance_duration" => 6,
            ],
            [
                "name" => "Penundaan Kenaikan Gaji Berkala Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 1",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 6,
            ],
            [
                "name" => "Penundaan Kenaikan Pangkat Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 2",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 9,
            ],
            [
                "name" => "Penurunan Pangkat Setingkat Lebih Rendah Selama 1 (Satu) Tahun",
                "description" => "Hukuman Disiplin Tingkat Sedang 3",
                "performance_allowance_deduction" => "0.5",
                "performance_allowance_duration" => 12,
            ],
            [
                "name" => "Penurunan Pangkat Setingkat Lebih Rendah Selama 3 (Tiga) Tahun",
                "description" => "Hukuman Disiplin Tingkat Berat 1",
                "performance_allowance_deduction" => "0.75",
                "performance_allowance_duration" => 12,
            ],
            [
                "name" => "Pemindahan Dalam Rangka Penurunan Jabatan Setingkat Lebih Rendah",
                "description" => "Hukuman Disiplin Tingkat Berat 2",
                "performance_allowance_deduction" => "0.9",
                "performance_allowance_duration" => 12,
            ],
            [
                "name" => "Pembebasan Dari Jabatan",
                "description" => "Hukuman Disiplin Tingkat Berat 3",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
            [
                "name" => "Pemberhentian Dengan Hormat Tidak Atas Permintaan Sendiri Sebagai PNS",
                "description" => "Hukuman Disiplin Tingkat Berat 4",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
            [
                "name" => "Pemberhentian Tidak Dengan Hormat Sebagai PNS",
                "description" => "Hukuman Disiplin Tingkat Berat 5",
                "performance_allowance_deduction" => null,
                "performance_allowance_duration" => null,
            ],
        ];
        DB::table('disciplinary_types')->insertTs($disciplinaryTypes);

    }
}
