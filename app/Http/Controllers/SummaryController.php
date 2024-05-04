<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @group Summary
 * Below is the comprehensive list of all data entities managed by the application:
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
     *
     * Below is the list of all data entities managed by the application.
     * @authenticated
     * @queryParam month integer Refers to the month between 1 - 12 of results being displayed. Default is '1'. Example: 1
     * @response 200 {"code": 200,"message": "success","data": {"users": [{"name": "Dr. Ir. Suprayoga Hadi, M.S.P.","photo_profile": "http://localhost/img/avatar.jpeg","date_of_birth": "12-12-1974"}],"total_government_employees": {"all": 294,"active": 288},"gender_employees": {"male": 155,"female": 133},"total_non_government_employees": {"assistance": 143,"outsourcing": 190},"work_unit": [{"name": "Kepala Sekretariat Wakil Presiden","quantity": 1}],"education_employees": [{"name": "Strata III","quantity": 8}]}}
     */
    public function index()
    {
        $users = DB::table('users');
        $users->whereMonth('date_of_birth', $this->request->month);
        $users->select('name', 'photo_profile', 'date_of_birth');
        $users = $users->take(8)->get();
        foreach ($users as $item) {
            $item->photo_profile = (is_null($item->photo_profile)) ? asset('img/avatar.jpeg') : Storage::disk('public')->url($item->photo_profile);
        }
        $data = [
            "users" => $users,
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
