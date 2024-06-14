<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 */
class RecapitulationNonAsnController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
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
        $total = $this->getTotal(2);
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
                    "total" => 24,
                    "cards" => [
                        [
                            "name" => "Tim Nasional Percepatan Penurunan Stunting (TPPS)",
                            "total" => 24,
                        ],
                    ],
                ],
                [
                    "name" => "Pendidikan",
                    "total" => $total->total_education,
                    "cards" => [
                        [
                            "name" => "Strata III",
                            "total" => $total->s3,
                        ],
                        [
                            "name" => "Strata II",
                            "total" => $total->s2,
                        ],
                        [
                            "name" => "Diploma IV/Strata I",
                            "total" => $total->s1,
                        ],
                        [
                            "name" => "Akademik/D3/S.Muda",
                            "total" => $total->d3,
                        ],
                        [
                            "name" => "Diploma I/II",
                            "total" => $total->d1,
                        ],
                        [
                            "name" => "SLTA/Sederajat",
                            "total" => $total->sma,
                        ],
                        [
                            "name" => "SLTP/Sederajat",
                            "total" => $total->smp,
                        ],
                        [
                            "name" => "SD/Sederajat",
                            "total" => $total->sd,
                        ],
                    ],
                ],
                [
                    "name" => "Jenis Kelamin",
                    "total" => $total->total_gender,
                    "cards" => [
                        [
                            "name" => "Laki-laki",
                            "total" => $total->male,
                        ],
                        [
                            "name" => "Perempuan",
                            "total" => $total->female,
                        ],
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * To get total
     *
     * @param string $type
     * @return void
     */
    private static function getTotal($type)
    {
        $total = DB::table('users');
        $total->select(
            DB::raw('COUNT(CASE WHEN gender IS NOT NULL THEN 1 END) as total_gender'),
            DB::raw('COUNT(CASE WHEN gender = 0 THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN gender = 1 THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN education_level IS NOT NULL THEN 1 END) as total_education'),
            DB::raw('COUNT(CASE WHEN education_level = 1 THEN 1 END) as sd'),
            DB::raw('COUNT(CASE WHEN education_level = 2 THEN 1 END) as smp'),
            DB::raw('COUNT(CASE WHEN education_level = 3 THEN 1 END) as sma'),
            DB::raw('COUNT(CASE WHEN education_level = 4 THEN 1 END) as d1'),
            DB::raw('COUNT(CASE WHEN education_level = 5 THEN 1 END) as d3'),
            DB::raw('COUNT(CASE WHEN education_level = 6 THEN 1 END) as s1'),
            DB::raw('COUNT(CASE WHEN education_level = 7 THEN 1 END) as s2'),
            DB::raw('COUNT(CASE WHEN education_level = 8 THEN 1 END) as s3'),
        );
        $total->where('type', $type);
        $total->whereIn('employment_status', [1, 6, 7, 8]);
        return $total = $total->first();
    }

    /**
     * To get total of grade
     *
     * @param string $type
     * @return void
     */
    private static function getGrade($type)
    {
        $grade = DB::table('grades as g');
        $grade->join('users as u', 'u.grade_id', '=', 'g.id');
        $grade->select(
            'g.name',
            'g.code',
            DB::raw('COUNT(u.id) as total')
        );
        $grade->where('g.type', $type);
        $grade->whereIn('u.employment_status', [1, 6, 7, 8]);
        $grade->groupBy('u.grade_id');
        $grade->orderBy('g.id', 'asc');
        return $grade = $grade->get();
    }
}
