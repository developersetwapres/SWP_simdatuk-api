<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecognitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recognitions')->delete();
        $recognitions = [
            ['id' => 1, 'name' => 'Satyalancana Karya Satya 10th', 'description' => 'ASN yang telah berbakti selama 10 tahun'],
            ['id' => 2, 'name' => 'Satyalancana Karya Satya 20th', 'description' => 'ASN yang telah berbakti selama 20 tahun'],
            ['id' => 3, 'name' => 'Satyalancana Karya Satya 30th', 'description' => 'ASN yang telah berbakti selama 30 tahun'],
            ['id' => 4, 'name' => 'Satyalancana Wira Karya', 'description' => 'ASN yang telah berjasa dan berbakti kepada negara'],
            ['id' => 5, 'name' => 'Bintang Jasa Utama', 'description' => 'ASN yang berjasa besar terhadap negara dan bangsa dalam suatu bidang, peristiwa, atau hal tertentu'],
        ];
        DB::table('recognitions')->insertTs($recognitions);
    }
}
