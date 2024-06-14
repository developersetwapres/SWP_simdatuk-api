<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 */
class RecapitulationAsnController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
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

        $nonactive = DB::table('users');
        $nonactive->select(
            DB::raw('COUNT(CASE WHEN employment_status = 7 || employment_status = 8 THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 7 THEN 1 END) as cltn'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 8 THEN 1 END) as tbln'),
        );
        $nonactive = $nonactive->first();

        $grade = $this->getGrade(1);
        $gradePPK = $this->getGrade(2);
        $total = $this->getTotal(1);

        $data = [
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
                "total" => 283,
                "cards" => $grade,
            ],
            [
                "name" => "Golongan PPPK",
                "total" => 2,
                "cards" => $gradePPK,
            ],
            [
                "name" => "Pegawai Non Aktif",
                "total" => $nonactive->total,
                "cards" => [
                    [
                        "name" => "TBLN",
                        "total" => $nonactive->tbln,
                    ],
                    [
                        "name" => "CLTN",
                        "total" => $nonactive->cltn,
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
