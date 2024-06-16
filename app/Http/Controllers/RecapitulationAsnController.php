<?php

namespace App\Http\Controllers;

use App\Repositories\RecapitulationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 */
class RecapitulationAsnController extends Controller
{
    protected $recapitulationRepository;

    public function __construct(
        Request $request,
        RecapitulationRepository $recapitulationRepository
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->recapitulationRepository = $recapitulationRepository;
    }

    /**
     * Get List of Recap ASN
     *
     * This endpoint is used to retrieve summary data.
     * @subgroup Recapitulation ASN
     * @authenticated
     * @response 200
     */
    public function index()
    {
        $position = DB::table('position_echelons as pe');
        $position->join('echelons as e', 'pe.echelon_id', '=', 'e.id');
        $position->select(
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (1, 2, 3, 4, 9) THEN filled ELSE 0 END) AS total'),
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (1, 2) THEN filled ELSE 0 END) AS total_pimpinan_tinggi'),
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (3, 4, 9) THEN filled ELSE 0 END) AS total_administrasi'),
        );
        $position->orderBy('e.id', 'asc');
        $position = $position->first();

        $nonActive = $this->recapitulationRepository->getNonActiveAsn();
        $grade = $this->recapitulationRepository->getGrade(1);
        $gradePPPK = $this->recapitulationRepository->getGrade(2);
        $educationAndGender = $this->recapitulationRepository->getEducationAndGender(1);

        $data = [
            "name" => "Rekapitulasi Pegawai ASN",
            "total" => 10,
            "cards" => [
                [
                    "name" => "Unit Kerja",
                    "total" => 283,
                    "cards" => [
                        [
                            "name" => "Kepala Sektretariat Wakil Presiden",
                            "total" => 1,
                        ],
                        [
                            "name" => "Deputi Bidang Dukungan Kebijakan Pembangunan Ekonomi dan Peningkatan Daya Saing",
                            "total" => 24,
                        ],
                        [
                            "name" => "Deputi Bidang Dukungan Kebijakan Pembangunan Manusia dan Pemerataan Pembangunan",
                            "total" => 26,
                        ],
                        [
                            "name" => "Deputi Bidang Dukungan Kebijakan Pemerintahan dan Wawasan Kebangsaan",
                            "total" => 31,
                        ],
                        [
                            "name" => "Deputi Bidang Administrasi",
                            "total" => 186,
                        ],
                        [
                            "name" => "Kementerian Sekretariat Negara",
                            "total" => 15,
                        ],
                    ],
                ],
                [
                    "name" => "Keterangan Jabatan",
                    "total" => $position->total,
                    "cards" => [
                        [
                            "name" => "Jabatan Pimpinan Tinggi",
                            "total" => $position->total_pimpinan_tinggi,
                        ],
                        [
                            "name" => "Jabatan Administrasi",
                            "total" => $position->total_administrasi,
                        ],
                        [
                            "name" => "Jabatan Fungsional",
                            "total" => 121,
                        ],
                    ],
                ],
                [
                    "name" => "Golongan ASN",
                    "total" => $grade[0],
                    "cards" => $grade[1],
                ],
                [
                    "name" => "Golongan PPPK",
                    "total" => $gradePPPK[0],
                    "cards" => $gradePPPK[1],
                ],
                [
                    "name" => "Pegawai Non Aktif",
                    "total" => $nonActive->total,
                    "cards" => [
                        [
                            "name" => "TBLN",
                            "total" => $nonActive->tbln,
                        ],
                        [
                            "name" => "CLTN",
                            "total" => $nonActive->cltn,
                        ],
                    ],
                ],
                [
                    "name" => "Pendidikan",
                    "total" => $educationAndGender->total_education,
                    "cards" => [
                        [
                            "name" => "Strata III",
                            "total" => $educationAndGender->s3,
                        ],
                        [
                            "name" => "Strata II",
                            "total" => $educationAndGender->s2,
                        ],
                        [
                            "name" => "Diploma IV/Strata I",
                            "total" => $educationAndGender->s1,
                        ],
                        [
                            "name" => "Akademik/D3/S.Muda",
                            "total" => $educationAndGender->d3,
                        ],
                        [
                            "name" => "Diploma I/II",
                            "total" => $educationAndGender->d1,
                        ],
                        [
                            "name" => "SLTA/Sederajat",
                            "total" => $educationAndGender->sma,
                        ],
                        [
                            "name" => "SLTP/Sederajat",
                            "total" => $educationAndGender->smp,
                        ],
                        [
                            "name" => "SD/Sederajat",
                            "total" => $educationAndGender->sd,
                        ],
                    ],
                ],
                [
                    "name" => "Jenis Kelamin",
                    "total" => $educationAndGender->total_gender,
                    "cards" => [
                        [
                            "name" => "Laki-laki",
                            "total" => $educationAndGender->male,
                        ],
                        [
                            "name" => "Perempuan",
                            "total" => $educationAndGender->female,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Get List of Recap ASN by Category
     *
     * This endpoint is used to retrieve summary data based on the category parameter.
     * @subgroup Recapitulation ASN
     * @authenticated
     * @urlParam category integer Refers to the category of results being displayed. Example: 1
     * @response 200
     */
    public function show()
    {
        if ($this->request->category == 1) {
            return $this->getCategory1();
        } else if ($this->request->category == 2) {
            return $this->getCategory2();
        } else {
            return $this->getCategory3();
        }
    }

    private function getCategory1()
    {
        $data = [
            "name" => "Jabatan Pimpinan Tinggi",
            "total" => 18,
            "cards" => [
                [
                    "name" => "Jabatan Pimpinan Tinggi",
                    "total" => "18",
                    "cards" => [
                        [
                            "name" => "Jabatan Pimpinan Tinggi Madya",
                            "total" => 4,
                        ],
                        [
                            "name" => "Jabatan Pimpinan Tinggi Pratama",
                            "total" => 4,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory2()
    {
        $data = [
            "name" => "Jabatan Administrasi",
            "total" => 128,
            "cards" => [
                [
                    "name" => "Jabatan Administrasi",
                    "total" => "128",
                    "cards" => [
                        [
                            "name" => "Jabatan Jabatan Administrasi",
                            "total" => 10,
                        ],
                        [
                            "name" => "Jabatan Pengawas",
                            "total" => 23,
                        ],
                        [
                            "name" => "Jabatan Pelaksana",
                            "total" => 23,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory3()
    {
        $data = [
            "name" => "Jabatan Fungsional",
            "total" => 128,
            "cards" => [
                [
                    "name" => "Jabatan Fungsional Analis Kebijakan",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Utama",
                            "total" => 1,
                        ],
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Arsiparis",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 1,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 2,
                        ],
                        [
                            "name" => "Penyelia",
                            "total" => 3,
                        ],
                        [
                            "name" => "Mahir",
                            "total" => 3,
                        ],
                        [
                            "name" => "Terampil",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Pranata Humas",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                        [
                            "name" => "Penyelia",
                            "total" => 3,
                        ],
                        [
                            "name" => "Mahir",
                            "total" => 3,
                        ],
                        [
                            "name" => "Terampil",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Penerjemah",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Analis Pengelolaan Keuangan APBN",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Pranata Keuangan APBN",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Analis Anggaran",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Analis SDM Aparatur",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Pranata SDM Aparatur",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Pranata Komputer",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Jabatan Fungsional Pengelolaan Pengadaan Barang / Jasa",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Madya",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                        [
                            "name" => "Ahli Pertama",
                            "total" => 3,
                        ],
                    ],
                ],
                [
                    "name" => "Dokter",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Dokter Gigi",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Perawat",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Perawat Gigi",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Asisten Apoteker",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Pustakawan",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
                [
                    "name" => "Manggala Informatika",
                    "total" => 8,
                    "cards" => [
                        [
                            "name" => "Ahli Muda",
                            "total" => 2,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }
}
