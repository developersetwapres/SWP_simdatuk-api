<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('residences')->delete();
        $residences = [
            ['id' => 1, 'name' => 'Luar Komplek'],
            ['id' => 2, 'name' => 'Cempaka Putih (Komplek Setneg Cempaka Putih, Jakarta Pusat)'],
            ['id' => 3, 'name' => 'Sunter (Komplek Setneg Sunter Agung, Tanjung Priok, Jakarta Utara)'],
            ['id' => 4, 'name' => 'Cidodol (Komplek Setneg Cidodol, Grogol Selatan, Keb.Lama, Jakarta Selatan)'],
            ['id' => 5, 'name' => 'Ciledug (Komplek Setneg, Pd. Kacang Barat, Pd. Aren, Tangerang)'],
            ['id' => 6, 'name' => 'Suradita (Komplek Setneg, Perumahan Suradita, Cisauk, Tangerang)'],
            ['id' => 7, 'name' => 'Karawaci (Komplek Setneg, Bencongan Indah, Kelapa Dua, Tangerang)'],
            ['id' => 8, 'name' => 'Plumpang (Komplek Setneg, Tugu Utara, Koja, Jakarta Utara)'],
            ['id' => 9, 'name' => 'Jonggol (Komplek Setwapres, Perum.Griya Merselina, Desa Suka Galih, Kec. Jonggol, Kab. Bogor)'],
            ['id' => 10, 'name' => 'Cipondoh (Komplek Setneg, Panunggangan Utara, Kec. Pinang, Tangerang)'],
        ];
        DB::table('residences')->insertTs($residences);
    }
}
