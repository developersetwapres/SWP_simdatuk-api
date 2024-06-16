<?php

namespace App\Http\Controllers;

use App\Repositories\RecapitulationRepository;
use Illuminate\Http\Request;

/**
 * @group Summary
 */
class RecapitulationNonAsnController extends Controller
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
     * Get List of Recap Non ASN
     *
     * This endpoint is used to retrieve summary data.
     * @subgroup Recapitulation Non ASN
     * @authenticated
     * @response 200
     */
    public function index()
    {
        $educationAndGender = $this->recapitulationRepository->getEducationAndGender(2);
        $tim = $this->recapitulationRepository->getTim(15);
        $data = [
            "name" => "Rekapitulasi Pegawai Non ASN",
            "total" => 10,
            "cards" => [
                [
                    "name" => "Jabatan",
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
                            "total" => 10,
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
                [
                    "name" => "Pendidikan",
                    "total" => $educationAndGender->total_education,
                    "cards" => [
                        [
                            "id" => 8,
                            "name" => "Strata III",
                            "total" => $educationAndGender->s3,
                        ],
                        [
                            "id" => 7,
                            "name" => "Strata II",
                            "total" => $educationAndGender->s2,
                        ],
                        [
                            "id" => 6,
                            "name" => "Diploma IV/Strata I",
                            "total" => $educationAndGender->s1,
                        ],
                        [
                            "id" => 5,
                            "name" => "Akademik/D3/S.Muda",
                            "total" => $educationAndGender->d3,
                        ],
                        [
                            "id" => 4,
                            "name" => "Diploma I/II",
                            "total" => $educationAndGender->d1,
                        ],
                        [
                            "id" => 3,
                            "name" => "SLTA/Sederajat",
                            "total" => $educationAndGender->sma,
                        ],
                        [
                            "id" => 2,
                            "name" => "SLTP/Sederajat",
                            "total" => $educationAndGender->smp,
                        ],
                        [
                            "id" => 1,
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
                            "id" => 1,
                            "name" => "Laki-laki",
                            "total" => $educationAndGender->male,
                        ],
                        [
                            "id" => 0,
                            "name" => "Perempuan",
                            "total" => $educationAndGender->female,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }
}
