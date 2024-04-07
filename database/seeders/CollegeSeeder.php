<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colleges')->delete();
        $colleges = [
            [
                "name" => "Universitas Gadjah Mada",
                "region" => false,
                "address" => "Daerah Istimewa Yogyakarta",
            ],
            [
                "name" => "Universitas Indonesia",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Universitas Sumatera Utara",
                "region" => false,
                "address" => "Sumatera Utara",
            ],
            [
                "name" => "Universitas Airlangga",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Hasanuddin",
                "region" => false,
                "address" => "Sulawesi Selatan",
            ],
            [
                "name" => "Universitas Andalas",
                "region" => false,
                "address" => "Sumatera Barat",
            ],
            [
                "name" => "Universitas Padjadjaran",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Universitas Diponegoro",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Universitas Sriwijaya",
                "region" => false,
                "address" => "Sumatera Selatan",
            ],
            [
                "name" => "Universitas Lambung Mangkurat",
                "region" => false,
                "address" => "Kalimantan Selatan",
            ],
            [
                "name" => "Universitas Syiah Kuala",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Universitas Sam Ratulangi",
                "region" => false,
                "address" => "Sulawesi Utara",
            ],
            [
                "name" => "Universitas Udayana",
                "region" => false,
                "address" => "Bali",
            ],
            [
                "name" => "Universitas Nusa Cendana",
                "region" => false,
                "address" => "Nusa Tenggara Timur",
            ],
            [
                "name" => "Universitas Mulawarman",
                "region" => false,
                "address" => "Kalimantan Timur",
            ],
            [
                "name" => "Universitas Mataram",
                "region" => false,
                "address" => "Nusa Tenggara Barat",
            ],
            [
                "name" => "Universitas Riau",
                "region" => false,
                "address" => "Riau",
            ],
            [
                "name" => "Universitas Cenderawasih",
                "region" => false,
                "address" => "Papua",
            ],
            [
                "name" => "Universitas Brawijaya",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Jambi",
                "region" => false,
                "address" => "Jambi",
            ],
            [
                "name" => "Universitas Pattimura",
                "region" => false,
                "address" => "Maluku",
            ],
            [
                "name" => "Universitas Tanjungpura",
                "region" => false,
                "address" => "Kalimantan Barat",
            ],
            [
                "name" => "Universitas Jenderal Soedirman",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Universitas Palangka Raya",
                "region" => false,
                "address" => "Kalimantan Tengah",
            ],
            [
                "name" => "Universitas Jember",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Lampung",
                "region" => false,
                "address" => "Lampung",
            ],
            [
                "name" => "Universitas Sebelas Maret",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Universitas Tadulako",
                "region" => false,
                "address" => "Sulawesi Tengah",
            ],
            [
                "name" => "Universitas Halu Oleo",
                "region" => false,
                "address" => "Sulawesi Tenggara",
            ],
            [
                "name" => "Universitas Bengkulu",
                "region" => false,
                "address" => "Bengkulu",
            ],
            [
                "name" => "Universitas Terbuka",
                "region" => false,
                "address" => "Banten",
            ],
            [
                "name" => "Universitas Negeri Padang",
                "region" => false,
                "address" => "Sumatera Barat",
            ],
            [
                "name" => "Universitas Negeri Malang",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Pendidikan Indonesia",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Universitas Negeri Manado",
                "region" => false,
                "address" => "Sulawesi Utara",
            ],
            [
                "name" => "Universitas Negeri Makassar",
                "region" => false,
                "address" => "Sulawesi Selatan",
            ],
            [
                "name" => "Universitas Negeri Jakarta",
                "region" => false,
                "address" => "DKI Jakarta",
            ],
            [
                "name" => "Universitas Negeri Yogyakarta",
                "region" => false,
                "address" => "Daerah Istimewa Yogyakarta",
            ],
            [
                "name" => "Universitas Negeri Surabaya",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Negeri Medan",
                "region" => false,
                "address" => "Sumatera Utara",
            ],
            [
                "name" => "Universitas Negeri Semarang",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Universitas Sultan Ageng Tirtayasa",
                "region" => false,
                "address" => "Banten",
            ],
            [
                "name" => "Universitas Trunojoyo Madura",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Khairun",
                "region" => false,
                "address" => "Maluku Utara",
            ],
            [
                "name" => "Universitas Papua",
                "region" => false,
                "address" => "Papua Barat",
            ],
            [
                "name" => "Universitas Malikussaleh",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Universitas Negeri Gorontalo",
                "region" => false,
                "address" => "Gorontalo",
            ],
            [
                "name" => "Universitas Pendidikan Ganesha",
                "region" => false,
                "address" => "Bali",
            ],
            [
                "name" => "Universitas Bangka Belitung",
                "region" => false,
                "address" => "Bangka Belitung",
            ],
            [
                "name" => "Universitas Borneo Tarakan",
                "region" => false,
                "address" => "Kalimantan Utara",
            ],
            [
                "name" => "Universitas Musamus Merauke",
                "region" => false,
                "address" => "Papua Selatan",
            ],
            [
                "name" => "Universitas Maritim Raja Ali Haji",
                "region" => false,
                "address" => "Kepulauan Riau",
            ],
            [
                "name" => "Universitas Samudra",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Universitas Sulawesi Barat",
                "region" => false,
                "address" => "Sulawesi Barat",
            ],
            [
                "name" => "Universitas Sembilanbelas November",
                "region" => false,
                "address" => "Sulawesi Tenggara",
            ],
            [
                "name" => "Universitas Tidar",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Universitas Siliwangi",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Universitas Teuku Umar",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Universitas Pembangunan Nasional \"Veteran\" Jawa Timur",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Universitas Timor",
                "region" => false,
                "address" => "Nusa Tenggara Timur",
            ],
            [
                "name" => "Universitas Pembangunan Nasional \"Veteran\" Jakarta",
                "region" => false,
                "address" => "DKI Jakarta",
            ],
            [
                "name" => "Universitas Pembangunan Nasional \"Veteran\" Yogyakarta",
                "region" => false,
                "address" => "Daerah Istimewa Yogyakarta",
            ],
            [
                "name" => "Universitas Singaperbangsa Karawang",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Institut Teknologi Bandung",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Institut Teknologi Sepuluh Nopember",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Institut Pertanian Bogor",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Institut Seni Indonesia Yogyakarta",
                "region" => false,
                "address" => "Daerah Istimewa Yogyakarta",
            ],
            [
                "name" => "Institut Seni Indonesia Denpasar",
                "region" => false,
                "address" => "Bali",
            ],
            [
                "name" => "Institut Seni Indonesia Surakarta",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Institut Seni Indonesia Padang Panjang",
                "region" => false,
                "address" => "Sumatera Barat",
            ],
            [
                "name" => "Institut Seni Budaya Indonesia Bandung",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Institut Seni Budaya Indonesia Aceh",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Institut Seni Budaya Indonesia Tanah Papua",
                "region" => false,
                "address" => "Papua",
            ],
            [
                "name" => "Institut Teknologi Kalimantan",
                "region" => false,
                "address" => "Kalimantan Timur",
            ],
            [
                "name" => "Institut Teknologi Sumatera",
                "region" => false,
                "address" => "Lampung",
            ],
            [
                "name" => "Institut Teknologi Bacharuddin Jusuf Habibie",
                "region" => false,
                "address" => "Sulawesi Selatan",
            ],
            [
                "name" => "Politeknik Manufaktur Bandung",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Politeknik Negeri Jakarta",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Politeknik Negeri Medan",
                "region" => false,
                "address" => "Sumatera Utara",
            ],
            [
                "name" => "Politeknik Negeri Bandung",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Politeknik Negeri Semarang",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Politeknik Negeri Sriwijaya",
                "region" => false,
                "address" => "Sumatera Selatan",
            ],
            [
                "name" => "Politeknik Negeri Lampung",
                "region" => false,
                "address" => "Lampung",
            ],
            [
                "name" => "Politeknik Negeri Ambon",
                "region" => false,
                "address" => "Maluku",
            ],
            [
                "name" => "Politeknik Negeri Padang",
                "region" => false,
                "address" => "Sumatera Barat",
            ],
            [
                "name" => "Politeknik Negeri Bali",
                "region" => false,
                "address" => "Bali",
            ],
            [
                "name" => "Politeknik Negeri Pontianak",
                "region" => false,
                "address" => "Kalimantan Barat",
            ],
            [
                "name" => "Politeknik Negeri Ujung Pandang",
                "region" => false,
                "address" => "Sulawesi Selatan",
            ],
            [
                "name" => "Politeknik Negeri Manado",
                "region" => false,
                "address" => "Sulawesi Utara",
            ],
            [
                "name" => "Politeknik Perkapalan Negeri Surabaya",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Negeri Banjarmasin",
                "region" => false,
                "address" => "Kalimantan Selatan",
            ],
            [
                "name" => "Politeknik Negeri Lhokseumawe",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Politeknik Negeri Kupang",
                "region" => false,
                "address" => "Nusa Tenggara Timur",
            ],
            [
                "name" => "Politeknik Elektronika Negeri Surabaya",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Negeri Jember",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Pertanian Negeri Pangkajene Kepulauan",
                "region" => false,
                "address" => "Sulawesi Selatan",
            ],
            [
                "name" => "Politeknik Pertanian Negeri Kupang",
                "region" => false,
                "address" => "Nusa Tenggara Timur",
            ],
            [
                "name" => "Politeknik Perikanan Negeri Tual",
                "region" => false,
                "address" => "Maluku",
            ],
            [
                "name" => "Politeknik Negeri Malang",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Pertanian Negeri Samarinda",
                "region" => false,
                "address" => "Kalimantan Timur",
            ],
            [
                "name" => "Politeknik Pertanian Negeri Payakumbuh",
                "region" => false,
                "address" => "Sumatera Barat",
            ],
            [
                "name" => "Politeknik Negeri Samarinda",
                "region" => false,
                "address" => "Kalimantan Timur",
            ],
            [
                "name" => "Politeknik Negeri Media Kreatif",
                "region" => false,
                "address" => "DKI Jakarta",
            ],
            [
                "name" => "Politeknik Manufaktur Negeri Bangka Belitung",
                "region" => false,
                "address" => "Kepulauan Bangka Belitung",
            ],
            [
                "name" => "Politeknik Negeri Batam",
                "region" => false,
                "address" => "Kepulauan Riau",
            ],
            [
                "name" => "Politeknik Negeri Nusa Utara",
                "region" => false,
                "address" => "Sulawesi Utara",
            ],
            [
                "name" => "Politeknik Negeri Bengkalis",
                "region" => false,
                "address" => "Riau",
            ],
            [
                "name" => "Politeknik Negeri Balikpapan",
                "region" => false,
                "address" => "Kalimantan Timur",
            ],
            [
                "name" => "Politeknik Negeri Madura",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Maritim Negeri Indonesia",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Politeknik Negeri Banyuwangi",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Negeri Madiun",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Politeknik Negeri Fakfak",
                "region" => false,
                "address" => "Papua Barat",
            ],
            [
                "name" => "Politeknik Negeri Sambas",
                "region" => false,
                "address" => "Kalimantan Barat",
            ],
            [
                "name" => "Politeknik Negeri Tanah Laut",
                "region" => false,
                "address" => "Kalimantan Selatan",
            ],
            [
                "name" => "Politeknik Negeri Subang",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Politeknik Negeri Ketapang",
                "region" => false,
                "address" => "Kalimantan Barat",
            ],
            [
                "name" => "Politeknik Negeri Cilacap",
                "region" => false,
                "address" => "Jawa Tengah",
            ],
            [
                "name" => "Politeknik Negeri Indramayu",
                "region" => false,
                "address" => "Jawa Barat",
            ],
            [
                "name" => "Politeknik Negeri Nunukan",
                "region" => false,
                "address" => "Kalimantan Utara",
            ],
            [
                "name" => "Akademi Komunitas Negeri Pacitan",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Akademi Komunitas Negeri Putra Sang Fajar Blitar",
                "region" => false,
                "address" => "Jawa Timur",
            ],
            [
                "name" => "Akademi Komunitas Negeri Aceh Barat",
                "region" => false,
                "address" => "Aceh",
            ],
            [
                "name" => "Akademi Komunitas Negeri Rejang Lebong",
                "region" => false,
                "address" => "Bengkulu",
            ],
            [
                "name" => "Akademi Komunitas Negeri Seni dan Budaya Yogyakarta",
                "region" => false,
                "address" => "Daerah Istimewa Yogyakarta",
            ],
            [
                "name" => "Harvard University",
                "region" => true,
                "address" => "Cambridge, Massachusetts, United States",
            ],
            [
                "name" => "University of Oxford",
                "region" => true,
                "address" => "Oxford, Oxfordshire, England",
            ],
            [
                "name" => "Stanford University",
                "region" => true,
                "address" => "Stanford, California, United States",
            ],
            [
                "name" => "Massachusetts Institute of Technology (MIT)",
                "region" => true,
                "address" => "Cambridge, Massachusetts, United States",
            ],
            [
                "name" => "California Institute of Technology (Caltech)",
                "region" => true,
                "address" => "Pasadena, California, United States",
            ],
            [
                "name" => "University of Cambridge",
                "region" => true,
                "address" => "Cambridge, Cambridgeshire, England",
            ],
            [
                "name" => "ETH Zurich - Swiss Federal Institute of Technology",
                "region" => true,
                "address" => "Zurich, Switzerland",
            ],
            [
                "name" => "University of California, Berkeley",
                "region" => true,
                "address" => "Berkeley, California, United States",
            ],
            [
                "name" => "Princeton University",
                "region" => true,
                "address" => "Princeton, New Jersey, United States",
            ],
            [
                "name" => "University of Chicago",
                "region" => true,
                "address" => "Chicago, Illinois, United States",
            ],
            [
                "name" => "Yale University",
                "region" => true,
                "address" => "New Haven, Connecticut, United States",
            ],
            [
                "name" => "Imperial College London",
                "region" => true,
                "address" => "London, England",
            ],
            [
                "name" => "University of California, Los Angeles (UCLA)",
                "region" => true,
                "address" => "Los Angeles, California, United States",
            ],
            [
                "name" => "University College London (UCL)",
                "region" => true,
                "address" => "London, England",
            ],
            [
                "name" => "Columbia University",
                "region" => true,
                "address" => "New York City, New York, United States",
            ],
            [
                "name" => "University of Michigan",
                "region" => true,
                "address" => "Ann Arbor, Michigan, United States",
            ],
            [
                "name" => "University of Toronto",
                "region" => true,
                "address" => "Toronto, Ontario, Canada",
            ],
            [
                "name" => "University of Pennsylvania",
                "region" => true,
                "address" => "Philadelphia, Pennsylvania, United States",
            ],
            [
                "name" => "Johns Hopkins University",
                "region" => true,
                "address" => "Baltimore, Maryland, United States",
            ],
            [
                "name" => "National University of Singapore (NUS)",
                "region" => true,
                "address" => "Singapore",
            ],
        ];
        DB::table('colleges')->insertTs($colleges);
    }
}
