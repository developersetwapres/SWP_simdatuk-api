<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 */
class RecapitulationController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
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
        $users = DB::table('users');
        $users->select(
            DB::raw('COUNT(id) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 1 THEN 1 END) as asn_active'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status != 1 THEN 1 END) as asn_nonactive'),
            DB::raw('COUNT(CASE WHEN type = 2 THEN 1 END) as nonasn'),
            DB::raw('COUNT(CASE WHEN type = 3 THEN 1 END) as outsource'),
        );
        $users = $users->first();
        $data = [
            "name" => 'Komposisi Pegawai',
            "total" => $users->total,
            "cards" => [
                [
                    "name" => 'Aparatur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
                    "total" => $users->asn_active,
                ],
                [
                    "name" => 'Aparatur Sipil Negara (ASN) Non Aktif',
                    "total" => $users->asn_nonactive,
                ],
                [
                    "name" => 'Non Aparatur Sipil Negara (Non ASN) + Tim',
                    "total" => $users->nonasn,
                ],
                [
                    "name" => 'Tenaga Outsourcing dan Non Outsourcing',
                    "total" => $users->outsource,
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
        $users = DB::table('position_echelons as pe');
        $users->join('echelons as e', 'pe.echelon_id', '=', 'e.id');
        $users->select(
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (1, 2, 3, 4) THEN filled ELSE 0 END) AS total_pejabat_pimpinan'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 1 THEN filled ELSE 0 END) AS echelon1'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 2 THEN filled ELSE 0 END) AS echelon2'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 3 THEN filled ELSE 0 END) AS echelon3'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 4 THEN filled ELSE 0 END) AS echelon4'),
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (5, 6, 7, 8) THEN filled ELSE 0 END) AS total_pejabat_fungsional_keahlian'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 5 THEN filled ELSE 0 END) AS ahli_utama'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 6 THEN filled ELSE 0 END) AS ahli_madya'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 7 THEN filled ELSE 0 END) AS ahli_muda'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 8 THEN filled ELSE 0 END) AS ahli_pertama'),
            DB::raw('SUM(CASE WHEN pe.echelon_id IN (10, 11, 12, 13) THEN filled ELSE 0 END) AS total_pejabat_fungsional_keterampilan'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 10 THEN filled ELSE 0 END) AS penyelia'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 11 THEN filled ELSE 0 END) AS mahir'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 12 THEN filled ELSE 0 END) AS terampil'),
            DB::raw('SUM(CASE WHEN pe.echelon_id = 13 THEN filled ELSE 0 END) AS pemula'),
        );
        $users->orderBy('e.id', 'asc');
        $users = $users->first();

        $data = [
            "name" => 'Apartur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
            "total" => 283,
            "cards" => [
                [
                    "name" => 'Pejabat Pimpinan',
                    "total" => $users->total_pejabat_pimpinan,
                    "cards" => [
                        [
                            "name" => "Pejabat Pimpinan Tinggi Madya (Eselon I)",
                            "total" => $users->echelon1,
                        ],
                        [
                            "name" => "Pejabat Pimpinan Tinggi Pratama (Eselon II)",
                            "total" => $users->echelon2,
                        ],
                        [
                            "name" => "Pejabat Administrator (Eselon III)",
                            "total" => $users->echelon3,
                        ],
                        [
                            "name" => "Pejabat Pengawas (Eselon IV)",
                            "total" => $users->echelon4,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Pelaksana',
                    "total" => 95,
                    "cards" => [
                        [
                            "name" => "Pejabat Pelaksana Golongan IV",
                            "total" => 0,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Golongan III",
                            "total" => 47,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Golongan II",
                            "total" => 17,
                        ],
                        [
                            "name" => "Pejabat Pelaksana Perbantuan TNI dan POLRI",
                            "total" => 31,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Fungsional Keahlian',
                    "total" => $users->total_pejabat_fungsional_keahlian,
                    "cards" => [
                        [
                            "name" => "Pejabat Fungsional Ahli Utama",
                            "total" => $users->ahli_utama,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Madya",
                            "total" => $users->ahli_madya,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Muda",
                            "total" => $users->ahli_muda,
                        ],
                        [
                            "name" => "Pejabat Fungsional Ahli Pertama",
                            "total" => $users->ahli_pertama,
                        ],
                    ],
                ],
                [
                    "name" => 'Pejabat Fungsional Keterampilan',
                    "total" => $users->total_pejabat_fungsional_keterampilan,
                    "cards" => [
                        [
                            "name" => "Pejabat Fungsional Penyelia",
                            "total" => $users->penyelia,
                        ],
                        [
                            "name" => "Pejabat Fungsional Mahir",
                            "total" => $users->mahir,
                        ],
                        [
                            "name" => "Pejabat Fungsional Terampil",
                            "total" => $users->terampil,
                        ],
                        [
                            "name" => "Pejabat Fungsional Pemula",
                            "total" => $users->pemula,
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
        $users = DB::table('users');
        $users->select(
            DB::raw('COUNT(CASE WHEN employment_status IN (7, 8, 9) && type = 1 THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 7 THEN 1 END) as cltn'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 8 THEN 1 END) as tbln'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 9 THEN 1 END) as nonactive'),
        );
        $users = $users->first();
        $data = [
            "name" => 'Aparatur Sipil Negara (ASN) Non Aktif',
            "total" => $users->total,
            "cards" => [
                [
                    "name" => 'Aparatur Sipil Negara (ASN) Non Aktif',
                    "total" => $users->total,
                    "cards" => [
                        [
                            "name" => "Tugas Belajar Luar Negeri (TBLN)",
                            "total" => $users->tbln,
                        ],
                        [
                            "name" => "Cuti Diluar Tanggungan Negara (CLTN)",
                            "total" => $users->cltn,
                        ],
                        [
                            "name" => "Tidak Aktif (Non Jabatan)",
                            "total" => $users->nonactive,
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
                    "total" => 88,
                    "cards" => [
                        [
                            "name" => "Tim Nasional Percepatan Penurunan Stunting (TPPS)",
                            "total" => 24,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    private function getCategory4()
    {
        $data = [
            "name" => "Tenaga Outsourcing dan Non Outsourcing",
            "total" => 198,
            "cards" => [
                [
                    "name" => 'Tenaga Outsourcing',
                    "total" => 191,
                    "cards" => [
                        [
                            "name" => "Pengemudi",
                            "total" => 38,
                        ],
                        [
                            "name" => "Petugas Kebersihan Gedung",
                            "total" => 51,
                        ],
                        [
                            "name" => "Petugas Perawatan Kolam",
                            "total" => 2,
                        ],
                        [
                            "name" => "Petugas Taman",
                            "total" => 24,
                        ],
                        [
                            "name" => "Pramusaji/Pramubakti",
                            "total" => 39,
                        ],
                        [
                            "name" => "Teknisi Jaringan",
                            "total" => 2,
                        ],
                        [
                            "name" => "Teknisi Komputer",
                            "total" => 11,
                        ],
                        [
                            "name" => "Teknisi Mekanikal dan Elektrikal",
                            "total" => 24,
                        ],
                    ],
                ],
                [
                    "name" => 'Tenaga Non Outsourcing',
                    "total" => 7,
                    "cards" => [
                        [
                            "name" => "Teknisi Fotocopy",
                            "total" => 3,
                        ],
                        [
                            "name" => "Teknisi Road Blocker",
                            "total" => 2,
                        ],
                        [
                            "name" => "Teknisi Lift",
                            "total" => 2,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }
}
