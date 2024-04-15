<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Summary
 *
 * APIs for user management
 */
class SummaryController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Summaries
     * @group Summary
     * @authenticated
     * @queryParam month integer Refers to the month between 1 - 12 of results being displayed. Default is '1'. Example: 1
     * @response 200 {"code": 200,"message": "success","data": {"users": [{"name": "Dr. Ir. Suprayoga Hadi, M.S.P.","photo_profile": "http://localhost/img/avatar.jpeg","date_of_birth": "12-12-1974"}],"total_government_employees": {"all": 294,"active": 288},"gender_employees": {"male": 155,"female": 133},"total_non_government_employees": {"assistance": 143,"outsourcing": 190},"work_unit": [{"name": "Kepala Sekretariat Wakil Presiden","quantity": 1}],"education_employees": [{"name": "Strata III","quantity": 8}]}}
     */
    public function index()
    {
        $users = DB::table('users');
        $users->where('tanggal_lahir', $this->request->month);
        $users = $users->take(8)->get();
        $data = [
            "users" => [
                [
                    "name" => "Dr. Ir. Suprayoga Hadi, M.S.P.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Ade Ulfah Rahayu Ningsih, S.E.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Ayu Pudianingtias, S.E., M.P.A.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Dr. Ir. Suprayoga Hadi, M.S.P.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Ade Ulfah Rahayu Ningsih, S.E.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Ayu Pudianingtias, S.E., M.P.A.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
                [
                    "name" => "Ayu Pudianingtias, S.E., M.P.A.",
                    "photo_profile" => asset('img/avatar.jpeg'),
                    "date_of_birth" => '12-12-1974',
                ],
            ],
            "total_government_employees" => [
                "all" => 294,
                "active" => 288,
            ],
            "gender_employees" => [
                "male" => 155,
                "female" => 133,
            ],
            "total_non_government_employees" => [
                "assistance" => 143,
                "outsourcing" => 190,
            ],
            "work_unit" => [
                [
                    "name" => 'Kepala Sekretariat Wakil Presiden',
                    "quantity" => 1,
                ],
                [
                    "name" => 'Deputi Bidang Dukungan Kebijakan Pembangunan Ekonomi dan Peningkatan Daya Saing',
                    "quantity" => 24,
                ],
                [
                    "name" => 'Deputi Bidang Dukungan Kebijakan Pembangunan Manusia dan Pemerataan Pembangunan',
                    "quantity" => 26,
                ],
                [
                    "name" => 'Deputi Bidang Dukungan Kebijakan Pemerintah dan Wawasan Kebangsaan',
                    "quantity" => 31,
                ],
                [
                    "name" => 'Deputi Bidang Administrasi',
                    "quantity" => 186,
                ],
                [
                    "name" => 'Kementerian Sekretariat Negara',
                    "quantity" => 15,
                ],
            ],
            "education_employees" => [
                [
                    "name" => 'Strata III',
                    "quantity" => 8,
                ],
                [
                    "name" => 'Strata II',
                    "quantity" => 96,
                ],
                [
                    "name" => 'Diploma IV / Strata I',
                    "quantity" => 92,
                ],
                [
                    "name" => 'Akademi / Diploma III / Sarjana Muda',
                    "quantity" => 18,
                ],
                [
                    "name" => 'Diploma I / II',
                    "quantity" => 1,
                ],
                [
                    "name" => 'SLTA / Sederajat',
                    "quantity" => 67,
                ],
                [
                    "name" => 'SLTP / Sederajat',
                    "quantity" => 1,
                ],
            ],
        ];
        return $this->response(200, 'success', $data);
    }
}
