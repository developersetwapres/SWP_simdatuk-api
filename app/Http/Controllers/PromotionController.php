<?php

namespace App\Http\Controllers;

use App\Repositories\PromotionRepository;
use App\Repositories\PositionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * @group Promotion
 * Below is the endpoint to get Promotion data
 */
class PromotionController extends Controller
{

    protected $promotionRepository;
    protected $positionRepository;

    protected $request;
    protected $posted;

    public function __construct(
        Request $request,
        PromotionRepository $promotionRepository,
        PositionRepository $positionRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->promotionRepository = $promotionRepository;
        $this->positionRepository = $positionRepository;
    }

    /**
     * Get List of Promotions
     *
     * Below is the list of all data Promotions.
     * @authenticated
     * @queryParam id integer Refers to the id of parent data. Example: 1
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Staff Khusus Wakil Presiden","type": 1,"available": 0,"filled": 0,"children": 10,"entity": 2,"users": []},{"id": 2,"name": "Kepala Sekretariat Wakil Presiden","type": 1,"available": 1,"filled": 1,"children": 4,"entity": 1,"users": [{"id": 10578,"name": "Ahmad Erani Yustika","echelon_id": null,"echelon_effective_date": null,"grade_id": null,"grade_effective_date": "2022-10-01","employee_id_number": "197303221997021001","employee_registration_number": "197303221997021001"}]},{"id": 3,"name": "Pejabat Kemensetneg yang Diperbantukan di Sekretariat Wakil Presiden","type": 1,"available": 0,"filled": 0,"children": 4,"entity": 2,"users": []}]}
     */
    public function index()
    {
        $structural = $this->promotionRepository->getAvailablePosition(1, [1, 2, 3, 4, 9], []);
        $functional = $this->promotionRepository->getAvailablePosition(2, [], [
            'analis kebijakan',
            'arsiparis',
            'pranata humas',
            'penerjemah',
            'analis pengelolaan keuangan apbn',
            'pranata keuangan apbn',
            'analis anggaran',
            'pranata sdm aparatur',
            'analis sdm aparatur',
            'pranata komputer',
            'pengelola pengadaan barang / jasa',
        ]);

        $response = [];

        $response[] = $this->addSection(
            1,
            $structural,
            [
                [
                    "echelon_id" => 1,
                    "name" => "Jabatan Pimpinan Tinggi Madya (Eselon I)",
                ],
                [
                    "echelon_id" => 2,
                    "name" => "Jabatan Pimpinan Tinggi Pratama (Eselon II)",
                ],
            ],
            "Jabatan Pimpinan Tinggi"
        );

        // Jabatan Administrasi
        $response[] = $this->addSection(
            1,
            $structural,
            [
                [
                    "echelon_id" => 3,
                    "name" => "Administrator (Eselon III)",
                ],
                [
                    "echelon_id" => 4,
                    "name" => "Pengawas (Eselon IV)",
                ],
                [
                    "echelon_id" => 9,
                    "name" => "Pelaksana",
                ],
            ],
            "Jabatan Administrasi",
        );

        // Jabatan Fungsional Analis Kebijakan
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Analis Kebijakan",
        );

        // Jabatan Fungsional Arsiparis
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Arsiparis",
        );

        // Jabatan Fungsional Pranata Humas
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Pranata Humas",
        );

        // Jabatan Fungsional Penerjemah
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Penerjemah",
        );

        // Jabatan Fungsional Analis Pengelolaan Keuangan APBN
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Analis Pengelolaan Keuangan APBN",
        );

        // Jabatan Fungsional Pranata Keuangan APBN
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Pranata Keuangan APBN",
        );

        // Jabatan Fungsional Analis Anggaran
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Analis Anggaran",
        );

        // Jabatan Fungsional Analis SDM Aparatur
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Analis SDM Aparatur",
        );

        // Jabatan Fungsional Pranata SDM Aparatur
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Pranata SDM Aparatur",
        );

        // Jabatan Fungsional Pranata Komputer
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Pranata Komputer",
        );

        // Jabatan Fungsional Pengelola Pengadaan Barang / Jasa
        $response[] = $this->addSection(
            2,
            $functional,
            [],
            "Pengelola Pengadaan Barang / Jasa",
        );

        return $this->response(200, 'success', $response);
    }

    public function show()
    {
        $response = $this->promotionRepository->getPromotionByEchelonId($this->request->echelon_id, explode(",", $this->request->position_id));
        foreach ($response as $data) {
            $workUnit = collect($this->positionRepository->getRecursivePosition($data->position_id, 2))
                ->filter(function ($item) use ($data) {
                    return $item->id != $data->position_id;
                })->values()->all();

            if (sizeof($workUnit)) {
                $workUnit = $workUnit[0]->name;
            } else {
                $workUnit = '-';
            }
            $data->work_unit = $workUnit;
        }

        return $this->response(200, 'success', $response);
    }

    private function addSection($type, $data, $cards, $name)
    {
        // Jabatan Pimpinan Tinggi
        list($resultCards, $total) = $this->getCards(
            $data,
            $cards,
            $type == 1 ? null : $name,
        );

        return [
            "id" => $type == 1 ? null : $this->promotionRepository->getPositionIdByName($name),
            "name" => $type == 1 ? $name : "Jabatan Fungsional " . $name,
            "cards" => $resultCards,
            "total" => $total,
        ];
    }

    // $data: structural/functional data
    // $cards: given hardcoded id and card name
    private function getCards($data, $cards, $positionName = null)
    {
        $total = 0;
        $filteredData = array_values(array_filter($data, function ($item) use ($cards, $positionName) {
            if (!isset($positionName)) {
                return in_array($item->echelon_id, Arr::pluck($cards, "echelon_id"));
            } else {
                return $item->position_name == $positionName;
            }
        }));

        $resultCards = array_map(function ($item, $index) use ($cards, &$total, $positionName) {
            $result = [
                "id" => (int) $item->echelon_id,
                "name" => isset($positionName)
                    ? $item->echelon_name
                    : $cards[$index]["name"],
                "unoccupied" => (int) $item->unoccupied,
            ];
            $total += $item->unoccupied;
            return $result;
        }, $filteredData, array_keys($filteredData));

        return [$resultCards, $total];
    }
}
