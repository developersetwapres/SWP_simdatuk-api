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
     * Get List of Komposisi Pegawai
     *
     * Below is the list of all Komposisi Pegawai data managed by the application.
     * @authenticated
     * @response 200 {"code":200,"message":"success","data":[{"name":"Komposisi Pegawai","total":1399,"cards":[{"name":"Aparatur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana","total":270},{"name":"Aparatur Sipil Negara (ASN) Non Aktif","total":372},{"name":"Non Aparatur Sipil Negara (Non ASN) + Tim","total":393},{"name":"Tenaga Outsourcing dan Non Outsourcing","total":362}]}]}
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
            [
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
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Get List of ASN
     *
     * Below is the list of all ASN data managed by the application.
     * @authenticated
     * @response 200 {"code":200,"message":"success","data":[{"name":"Unit Kerja","total":283,"cards":[{"name":"Kepala Sektretariat Wakil Presiden","total":1},{"name":"Deputi Bidang Dukungan Kebijakan Pembangunan Ekonomi dan Peningkatan Daya Saing","total":24},{"name":"Deputi Bidang Dukungan Kebijakan Pembangunan Manusia dan Pemerataan Pembangunan","total":26},{"name":"Deputi Bidang Dukungan Kebijakan Pemerintahan dan Wawasan Kebangsaan","total":31},{"name":"Deputi Bidang Administrasi","total":186},{"name":"Kementerian Sekretariat Negara","total":15}]},{"name":"Keterangan Jabatan","total":268,"cards":[{"name":"Jabatan Pimpinan Tinggi","total":19},{"name":"Jabatan Administrasi","total":128},{"name":"Jabatan Fungsional","total":121}]},{"name":"Golongan ASN","total":283,"cards":[{"name":"Pembina Utama","code":"(IV/e)","total":11},{"name":"Pembina Utama Madya","code":"(IV/d)","total":22},{"name":"Pembina Utama Muda","code":"(IV/c)","total":29},{"name":"Pembina Tingkat I","code":"(IV/b)","total":67},{"name":"Pembina","code":"(IV/a)","total":56},{"name":"Penata Tingkat I","code":"(III/d)","total":87},{"name":"Penata","code":"(III/c)","total":35},{"name":"Penata Muda Tingkat I","code":"(III/b)","total":38},{"name":"Penata Muda","code":"(III/a)","total":40},{"name":"Pengatur Tingkat I","code":"(II/d)","total":49},{"name":"Pengatur","code":"(II/c)","total":27},{"name":"Pengatur Muda Tingkat I","code":"(II/b)","total":13},{"name":"Pengatur Muda","code":"(II/a)","total":17},{"name":"Juru Tingkat I","code":"(I/d)","total":4},{"name":"Juru","code":"(I/c)","total":1},{"name":"Juru Muda Tingkat I","code":"(I/b)","total":3}]},{"name":"Golongan PPPK","total":2,"cards":[{"name":"Golongan IX","code":"(IX)","total":1},{"name":"Golongan VII","code":"(VII)","total":2}]},{"name":"Pegawai Non Aktif","total":3,"cards":[{"name":"TBLN","total":3},{"name":"CLTN","total":0}]},{"name":"Pendidikan","total":283,"cards":[{"name":"Strata III","total":8},{"name":"Strata II","total":96}]},{"name":"Jenis Kelamin","total":392,"cards":[{"name":"Laki-laki","total":275},{"name":"Perempuan","total":117}]}]}
     */
    public function asn()
    {
        $nonactive = DB::table('users');
        $nonactive->select(
            DB::raw('COUNT(CASE WHEN employment_status = 7 || employment_status = 8 THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 7 THEN 1 END) as cltn'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 8 THEN 1 END) as tbln'),
        );
        $nonactive = $nonactive->first();
        $grade = $this->getGrade(1);
        $gradePPK = $this->getGrade(2);
        $gender = $this->getGender(2);
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
                "total" => 268,
                "cards" => [
                    [
                        "name" => "Jabatan Pimpinan Tinggi",
                        "total" => 19,
                    ],
                    [
                        "name" => "Jabatan Administrasi",
                        "total" => 128,
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
                "total" => 283,
                "cards" => [
                    [
                        "name" => "Strata III",
                        "total" => 8,
                    ],
                    [
                        "name" => "Strata II",
                        "total" => 96,
                    ],
                ],
            ],
            [
                "name" => "Jenis Kelamin",
                "total" => $gender->total,
                "cards" => [
                    [
                        "name" => "Laki-laki",
                        "total" => $gender->male,
                    ],
                    [
                        "name" => "Perempuan",
                        "total" => $gender->female,
                    ],
                ],
            ],
        ];

        return $this->response(200, 'success', $data);
    }

    /**
     * Get List of Non ASN
     *
     * Below is the list of all Non ASN data managed by the application.
     * @authenticated
     * @response 200 {"code":200,"message":"success","data":[{"name":"Jabatan","total":74,"cards":[{"name":"Staf Khusus Wakil Presiden","total":10},{"name":"Asisten Staf Khusus Wakil Presiden","total":20}]},{"name":"Tim","total":88,"cards":[{"name":"Tim Nasional Percepatan Penanggulangan Kemiskinan (TNP2K)","total":64},{"name":"Tim Nasional Percepatan Penurunan Stunting (TPPS)","total":24}]},{"name":"Pendidikan","total":88,"cards":[{"name":"Strata III","total":64},{"name":"Strata II","total":24}]},{"name":"Jenis Kelamin","total":392,"cards":[{"name":"Laki-laki","total":275},{"name":"Perempuan","total":117}]}]}
     */
    public function nonasn()
    {
        $gender = $this->getGender(2);
        $data = [
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
                ],
            ],
            [
                "name" => "Tim",
                "total" => 88,
                "cards" => [
                    [
                        "name" => "Tim Nasional Percepatan Penanggulangan Kemiskinan (TNP2K)",
                        "total" => 64,
                    ],
                    [
                        "name" => "Tim Nasional Percepatan Penurunan Stunting (TPPS)",
                        "total" => 24,
                    ],
                ],
            ],
            [
                "name" => "Pendidikan",
                "total" => 88,
                "cards" => [
                    [
                        "name" => "Strata III",
                        "total" => 64,
                    ],
                    [
                        "name" => "Strata II",
                        "total" => 24,
                    ],
                ],
            ],
            [
                "name" => "Jenis Kelamin",
                "total" => $gender->total,
                "cards" => [
                    [
                        "name" => "Laki-laki",
                        "total" => $gender->male,
                    ],
                    [
                        "name" => "Perempuan",
                        "total" => $gender->female,
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Get List of Outsource
     *
     * Below is the list of all Outsource data managed by the application.
     * @authenticated
     * @response 200 {"code":200,"message":"success","data":[{"name":"Tenaga Outsourcing","total":191,"cards":[{"name":"Pengemudi","total":38},{"name":"Petugas Kebersihan Gedung","total":51}]},{"name":"Tenaga Non Outsourcing","total":191,"cards":[{"name":"Teknisi Fotocopy","total":38},{"name":"Teknisi Road Blocker","total":51}]},{"name":"Pendidikan","total":191,"cards":[{"name":"Diploma IV/Strata I","total":38},{"name":"Akademi/Diploma III/Sarjana Muda","total":51}]},{"name":"Jenis Kelamin","total":312,"cards":[{"name":"Laki-laki","total":271},{"name":"Perempuan","total":41}]}]}
     */
    public function outsource()
    {
        $gender = $this->getGender(3);
        $data = [
            [
                "name" => "Tenaga Outsourcing",
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
                ],
            ],
            [
                "name" => "Tenaga Non Outsourcing",
                "total" => 191,
                "cards" => [
                    [
                        "name" => "Teknisi Fotocopy",
                        "total" => 38,
                    ],
                    [
                        "name" => "Teknisi Road Blocker",
                        "total" => 51,
                    ],
                ],
            ],
            [
                "name" => "Pendidikan",
                "total" => 191,
                "cards" => [
                    [
                        "name" => "Diploma IV/Strata I",
                        "total" => 38,
                    ],
                    [
                        "name" => "Akademi/Diploma III/Sarjana Muda",
                        "total" => 51,
                    ],
                ],
            ],
            [
                "name" => "Jenis Kelamin",
                "total" => $gender->total,
                "cards" => [
                    [
                        "name" => "Laki-laki",
                        "total" => $gender->male,
                    ],
                    [
                        "name" => "Perempuan",
                        "total" => $gender->female,
                    ],
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * To get total of gender
     *
     * @param string $type
     * @return void
     */
    private static function getGender($type)
    {
        $gender = DB::table('users');
        $gender->select(
            DB::raw('COUNT(CASE WHEN gender = 0 || gender = 1 THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN gender = 0 THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN gender = 1 THEN 1 END) as male'),
        );
        $gender->where('type', $type);
        return $gender = $gender->first();
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
        $grade->groupBy('u.grade_id');
        $grade->orderBy('g.id', 'asc');
        return $grade = $grade->get();
    }
}
