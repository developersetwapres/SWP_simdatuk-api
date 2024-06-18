<?php

namespace App\Http\Controllers;

use App\Repositories\RecapitulationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 */
class RecapitulationController extends Controller
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
     * Get List of Recap
     *
     * This endpoint is used to retrieve summary data.
     * @subgroup Recapitulation
     * @authenticated
     * @response 200
     */
    public function index()
    {
        $users = $this->getTotalRecapitulation();
        $data = [
            "name" => 'Komposisi Pegawai',
            "total" => $users->total,
            "cards" => [
                [
                    "name" => 'Komposisi Pegawai',
                    "total" => $users->total,
                    "cards" => [
                        [
                            "id" => 1,
                            "name" => 'Aparatur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
                            "total" => $users->asn_active,
                        ],
                        [
                            "id" => 2,
                            "name" => 'Aparatur Sipil Negara (ASN) Non Aktif',
                            "total" => $users->asn_nonactive,
                        ],
                        [
                            "id" => 3,
                            "name" => 'Non Aparatur Sipil Negara (Non ASN) + Tim',
                            "total" => $users->nonasn,
                        ],
                        [
                            "id" => 4,
                            "name" => 'Tenaga Outsourcing dan Non Outsourcing',
                            "total" => $users->outsource,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Get List of Recap by Category
     *
     * This endpoint is used to retrieve summary data based on the category parameter.
     * @subgroup Recapitulation
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
        } else if ($this->request->category == 3) {
            return $this->getCategory3();
        } else {
            return $this->getCategory4();
        }
    }

    private function getCategory1()
    {
        $pejabat = $this->recapitulationRepository->getPejabatPimpinanAndFungsional();
        $pelaksana = $this->recapitulationRepository->getPejabatPelaksana();

        $data = [
            "name" => 'Apartur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
            "total" => 283,
            "cards" => [
                [
                    "name" => 'Pejabat Pimpinan',
                    "total" => $pejabat->total_pejabat_pimpinan,
                    "cards" => [
                        [
                            "name" => "Pejabat Pimpinan Tinggi Madya (Eselon I)",
                            "total" => $pejabat->echelon1,
                        ],
                        [
                            "name" => "Pejabat Pimpinan Tinggi Pratama (Eselon II)",
                            "total" => $pejabat->echelon2,
                        ],
                        [
                            "name" => "Pejabat Administrator (Eselon III)",
                            "total" => $pejabat->echelon3,
                        ],
                        [
                            "name" => "Pejabat Pengawas (Eselon IV)",
                            "total" => $pejabat->echelon4,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Pelaksana',
                    "total" => $pelaksana->total,
                    "cards" => [
                        [
                            "name" => "Pejabat Pelaksana Golongan IV",
                            "total" => $pelaksana->golongan4,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Golongan III",
                            "total" => $pelaksana->golongan3,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Golongan II",
                            "total" => $pelaksana->golongan2,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Perbantuan TNI dan POLRI",
                            "total" => $pelaksana->tnipolri,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Fungsional Keahlian',
                    "total" => $pejabat->total_pejabat_fungsional_keahlian,
                    "cards" => [
                        [
                            "name" => "Pejabat Fungsional Ahli Utama",
                            "total" => $pejabat->ahli_utama,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Madya",
                            "total" => $pejabat->ahli_madya,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Muda",
                            "total" => $pejabat->ahli_muda,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Pertama",
                            "total" => $pejabat->ahli_pertama,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Fungsional Keterampilan',
                    "total" => $pejabat->total_pejabat_fungsional_keterampilan,
                    "cards" => [
                        [
                            "name" => "Pejabat Fungsional Penyelia",
                            "total" => $pejabat->penyelia,
                        ],
                        [
                            "name" => "Pejabat Fungsional Mahir",
                            "total" => $pejabat->mahir,
                        ],
                        [
                            "name" => "Pejabat Fungsional Terampil",
                            "total" => $pejabat->terampil,
                        ],
                        [
                            "name" => "Pejabat Fungsional Pemula",
                            "total" => $pejabat->pemula,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Kemensetneg Yang Diperbantukan di Setwapres',
                    "total" => 15,
                    "cards" => [
                        [
                            "name" => "Pejabat Struktural",
                            "total" => 1,
                        ],
                        [
                            "name" => "Pejabat Pelaksana",
                            "total" => 1,
                        ],
                        [
                            "name" => "Pejabat Fungsional",
                            "total" => 13,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory2()
    {
        $nonActive = $this->recapitulationRepository->getNonActiveAsn();
        $data = [
            "name" => 'Aparatur Sipil Negara (ASN) Non Aktif',
            "total" => $nonActive->total,
            "cards" => [
                [
                    "name" => "Aparatur Sipil Negara (ASN) Non Aktif",
                    "total" => $nonActive->total,
                    "cards" => [
                        [
                            "name" => "Tugas Belajar Luar Negeri (TBLN)",
                            "total" => $nonActive->tbln,
                        ],
                        [
                            "name" => "Cuti Diluar Tanggungan Negara (CLTN)",
                            "total" => $nonActive->cltn,
                        ],
                        [
                            "name" => "Tidak Aktif (Non Jabatan)",
                            "total" => $nonActive->nonactive,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory3()
    {
        $tim = $this->recapitulationRepository->getTim(15);
        $data = [
            "name" => 'Non Aparatur Sipil Negara (Non ASN) + Tim',
            "total" => 162,
            "cards" => [
                [
                    "name" => "Non Aparatur Sipil Negara (Non ASN)",
                    "total" => 74,
                    "cards" => [
                        [
                            "name" => "Staf Khusus Wakil Presiden",
                            "total" => 10,
                        ],
                        [
                            "name" => "Asisten Staf Khusus Wakil Presiden",
                            "total" => 20,
                        ],
                        [
                            "name" => "Pembantu Asisten Staf Khusus Wakil Presiden",
                            "total" => 5,
                        ],
                        [
                            "name" => "Anggota Tim Ahli Wakil Presiden",
                            "total" => 12,
                        ],
                        [
                            "name" => "Staf Pada Sekretaris Pribadi Istri Wakil Presiden",
                            "total" => 1,
                        ],
                        [
                            "name" => "Staf Kerumahtanggaan Pada Kediaman Wakil Presiden",
                            "total" => 1,
                        ],
                        [
                            "name" => "Sekretariat Pada Staf Khusus Wakil Presiden (PTT dari SETKAB)",
                            "total" => 3,
                        ],
                        [
                            "name" => "Ajudan Wakil Presiden dan Istri Wakil Presiden (Perbantuan TNI dan POLRI)",
                            "total" => 8,
                        ],
                        [
                            "name" => "Dokter Pribadi Wakil Presiden",
                            "total" => 4,
                        ],
                        [
                            "name" => "Pengemudi VVIP (Perbantuan TNI dan POLRI)",
                            "total" => 4,
                        ],
                    ],
                ],
                [
                    "name" => "Tim",
                    "total" => $tim,
                    "cards" => [
                        [
                            "id" => 15,
                            "name" => "Tim Nasional Percepatan Penurunan Stunting (TPPS)",
                            "total" => $tim,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory4()
    {
        $outsource = $this->recapitulationRepository->getOutsource(19);
        $nonOutsource = $this->recapitulationRepository->getOutsource(20);
        $data = [
            "name" => "Tenaga Outsourcing dan Non Outsourcing",
            "total" => $outsource[0] + $nonOutsource[0],
            "cards" => [
                [
                    "name" => 'Tenaga Outsourcing',
                    "total" => $outsource[0],
                    "cards" => $outsource[1],
                ],
                [
                    "name" => 'Tenaga Non Outsourcing',
                    "total" => $nonOutsource[0],
                    "cards" => $nonOutsource[1],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getTotalRecapitulation()
    {
        $users = DB::table('users');
        $users->select(
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6, 7, 8, 9) THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 AND (employment_status = 1 OR employment_status = 6) THEN 1 END) as asn_active'),
            DB::raw('COUNT(CASE WHEN type = 1 AND (employment_status = 7 OR employment_status = 8 OR employment_status = 9) THEN 1 END) as asn_nonactive'),
            DB::raw('COUNT(CASE WHEN type = 2 AND employment_status = 1 THEN 1 END) as nonasn'),
            DB::raw('COUNT(CASE WHEN type = 3 AND employment_status = 1 THEN 1 END) as outsource'),
        );
        return $users = $users->first();
    }
}
