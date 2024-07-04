<?php

namespace App\Http\Controllers;

use App\Repositories\PromotionRepository;
use App\Repositories\PositionRepository;
use Barryvdh\DomPDF\Facade\Pdf;
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
     * @response 200 {"code":200,"message":"success","data":[{"id":null,"name":"Jabatan Pimpinan Tinggi","cards":[{"id":1,"name":"Jabatan Pimpinan Tinggi Madya (Eselon I)","unoccupied":0},{"id":2,"name":"Jabatan Pimpinan Tinggi Pratama (Eselon II)","unoccupied":0}],"total":0},{"id":null,"name":"Jabatan Administrasi","cards":[{"id":3,"name":"Administrator (Eselon III)","unoccupied":0},{"id":4,"name":"Pengawas (Eselon IV)","unoccupied":0},{"id":9,"name":"Pelaksana","unoccupied":13}],"total":13},{"id":"41,45,49,53,58,62,66,69,74,79,84,89,93","name":"Jabatan Fungsional Analis Kebijakan","cards":[{"id":5,"name":"Ahli Utama","unoccupied":3},{"id":6,"name":"Ahli Madya","unoccupied":9},{"id":7,"name":"Ahli Muda","unoccupied":10},{"id":8,"name":"Ahli Pertama","unoccupied":16}],"total":38},{"id":"47,51,55,64,71,82,87,91,95,102,150,158,193,194,195,196,197,198,199,201,204,207","name":"Jabatan Fungsional Arsiparis","cards":[{"id":6,"name":"Ahli Madya","unoccupied":2},{"id":7,"name":"Ahli Muda","unoccupied":13},{"id":8,"name":"Ahli Pertama","unoccupied":12},{"id":10,"name":"Penyelia","unoccupied":11},{"id":11,"name":"Mahir","unoccupied":14},{"id":12,"name":"Terampil","unoccupied":13}],"total":65},{"id":"103,136","name":"Jabatan Fungsional Pranata Humas","cards":[{"id":6,"name":"Ahli Madya","unoccupied":2},{"id":7,"name":"Ahli Muda","unoccupied":0},{"id":8,"name":"Ahli Pertama","unoccupied":5},{"id":10,"name":"Penyelia","unoccupied":3},{"id":11,"name":"Mahir","unoccupied":4},{"id":12,"name":"Terampil","unoccupied":2}],"total":16},{"id":"202","name":"Jabatan Fungsional Penerjemah","cards":[{"id":6,"name":"Ahli Madya","unoccupied":2},{"id":7,"name":"Ahli Muda","unoccupied":4},{"id":8,"name":"Ahli Pertama","unoccupied":4}],"total":10},{"id":"142","name":"Jabatan Fungsional Analis Pengelolaan Keuangan APBN","cards":[{"id":6,"name":"Ahli Madya","unoccupied":0},{"id":7,"name":"Ahli Muda","unoccupied":0},{"id":8,"name":"Ahli Pertama","unoccupied":4}],"total":4},{"id":"205","name":"Jabatan Fungsional Pranata Keuangan APBN","cards":[{"id":10,"name":"Penyelia","unoccupied":2},{"id":11,"name":"Mahir","unoccupied":7},{"id":12,"name":"Terampil","unoccupied":4}],"total":13},{"id":"203","name":"Jabatan Fungsional Analis Anggaran","cards":[{"id":6,"name":"Ahli Madya","unoccupied":0},{"id":7,"name":"Ahli Muda","unoccupied":2},{"id":8,"name":"Ahli Pertama","unoccupied":2}],"total":4},{"id":"147","name":"Jabatan Fungsional Analis SDM Aparatur","cards":[{"id":6,"name":"Ahli Madya","unoccupied":0},{"id":7,"name":"Ahli Muda","unoccupied":0},{"id":8,"name":"Ahli Pertama","unoccupied":3}],"total":3},{"id":"208","name":"Jabatan Fungsional Pranata SDM Aparatur","cards":[{"id":10,"name":"Penyelia","unoccupied":3},{"id":11,"name":"Mahir","unoccupied":3},{"id":12,"name":"Terampil","unoccupied":1}],"total":7},{"id":"200,206","name":"Jabatan Fungsional Pranata Komputer","cards":[{"id":6,"name":"Ahli Madya","unoccupied":0},{"id":7,"name":"Ahli Muda","unoccupied":2},{"id":8,"name":"Ahli Pertama","unoccupied":6}],"total":8},{"id":"162","name":"Jabatan Fungsional Pengelola Pengadaan Barang / Jasa","cards":[{"id":6,"name":"Ahli Madya","unoccupied":1},{"id":7,"name":"Ahli Muda","unoccupied":0},{"id":8,"name":"Ahli Pertama","unoccupied":1}],"total":2}]}
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

    /**
     * Get Detail of Promotions
     *
     * Below is the detail of all data Promotions.
     * @authenticated
     * @queryParam position_id string Refers to the position_id of promotions. Example: 41,45,49,53,58,62,66,69,74,79,84,89,93
     * @queryParam echelon_id integer Refers to the echelon_id of promotions. Example: 8
     * @response 200 {"code":200,"message":"success","data":[{"id":4,"position_id":45,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Ekonomi dan Keuangan"},{"id":13,"position_id":49,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Industri, Perdagangan, Pariwisata, dan Ekonomi Kreatif"},{"id":21,"position_id":53,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Infrastruktur, Ketahanan Energi, dan Sumber Daya Alam"},{"id":30,"position_id":62,"name":"Ahli Pertama","unoccupied":1,"work_unit":"Asisten Deputi Penanggulangan Kemiskinan"},{"id":38,"position_id":66,"name":"Ahli Pertama","unoccupied":1,"work_unit":"Asisten Deputi Pembangunan Sumber Daya Manusia"},{"id":42,"position_id":69,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Pemberdayaan Masyarakat dan Penanggulangan Bencana"},{"id":51,"position_id":79,"name":"Ahli Pertama","unoccupied":1,"work_unit":"Asisten Deputi Hubungan Luar Negeri"},{"id":59,"position_id":84,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Politik, Hukum, dan Otonomi Daerah"},{"id":67,"position_id":89,"name":"Ahli Pertama","unoccupied":2,"work_unit":"Asisten Deputi Wawasan Kebangsaan, Pertahanan, dan Keamanan"},{"id":75,"position_id":93,"name":"Ahli Pertama","unoccupied":1,"work_unit":"Asisten Deputi Tata Kelola Pemerintahan"}]}
     */
    public function show()
    {
        $messages = [
            'position_id.regex' => 'Format ID jabatan yang dikirim tidak sesuai.',
            'echelon_id.required' => 'ID eselon harus dikirim',
            'echelon_id.numeric' => 'ID eselon harus berupa angka.',
        ];

        $this->request->validate([
            'position_id' => 'nullable|regex:/^\d+(,\d+)*$/',
            'echelon_id' => 'required|numeric',
        ], $messages);

        $response = $this->promotionRepository->getPromotionByEchelonId($this->request->echelon_id, isset($this->request->position_id) ? explode(",", $this->request->position_id) : []);
        foreach ($response as $data) {
            $shownHierarcy = '';
            //get last 3 parent
            $last3Parent = $this->positionRepository->getRecursivePosition($data->position_id, 4);
            $last3Parent = collect($last3Parent)->filter(function ($item) use ($data) {
                return $item->id != $data->position_id;
            })->reverse()->values()->all();

            if (sizeof($last3Parent)) {
                foreach ($last3Parent as $key => $value) {
                    if ($key > 0) {
                        $shownHierarcy .= " > ";
                    }
                    $shownHierarcy .= $value->name;
                }
            } else {
                $shownHierarcy = 'Sekretariat Wakil Presiden';
            }
            $data->hierarchy = $shownHierarcy;
        }

        return $this->response(200, 'success', $response);
    }

    /**
     * Get Filter of Promotions
     *
     * Below is for get user based on filter.
     * @authenticated
     * @queryParam group_id integer Refers to the id of groups. Example: 1
     * @queryParam echelon_id integer Refers to the id of echelons. Example: 1
     * @queryParam grade_id integer Refers to the id of grades. Example: 1
     * @queryParam education_level integer Refers to the level of education level employee. Example: 1
     * @queryParam max_age integer Refers to the max age of employee. Example: 1
     * @queryParam disciplinary_id integer Refers to the id of disciplinary_history. Example: 1
     * @queryParam target_predicate_id integer Refers to the employee_performance_predicate of target_history_users. Example: 1
     * @queryParam cpns_year integer Refers to the cpns_year of employee. Example: 1
     * @queryParam grade_year integer Refers to the date grade_effective_date of users. Example: 1
     * @queryParam credit_score integer Refers to the score of user_credits. Example: 1
     * @queryParam competency_point integer Refers to the point of user_competencies. Example: 1
     * @response 200 {"code":200,"message":"success","data":[{"id":1952,"echelon_name":null,"name":"Raden Nashrul Fathurrohman","grade_name":null,"employee_id_number":"11000036051078","employee_registration_number":"11000036051078"}]}
     */
    public function users()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
            'group_id.numeric' => 'ID grade harus berupa angka.',
            'echelon_id.numeric' => 'ID eselon harus berupa angka.',
            'grade_id.numeric' => 'ID eselon harus berupa angka.',
            'education_level.numeric' => 'ID eselon harus berupa angka.',
            'max_age.numeric' => 'ID eselon harus berupa angka.',
            'disciplinary_id.numeric' => 'ID eselon harus berupa angka.',
            'target_predicate_id.numeric' => 'ID eselon harus berupa angka.',
            'cpns_year.numeric' => 'ID eselon harus berupa angka.',
            'grade_year.numeric' => 'ID eselon harus berupa angka.',
            'credit_score.numeric' => 'ID eselon harus berupa angka.',
            'competency_point.numeric' => 'ID eselon harus berupa angka.',
        ];

        $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
            'group_id' => 'nullable|numeric',
            'echelon_id' => 'nullable|numeric',
            'grade_id' => 'nullable|numeric',
            'education_level' => 'nullable|numeric',
            'max_age' => 'nullable|numeric',
            'disciplinary_id' => 'nullable|numeric',
            'target_predicate_id' => 'nullable|numeric',
            'cpns_year' => 'nullable|numeric',
            'grade_year' => 'nullable|numeric',
            'credit_score' => 'nullable|numeric',
            'competency_point' => 'nullable|numeric',
        ], $messages);

        $users = $this->promotionRepository->getUserByFilter(
            $this->request->page,
            $this->request->limit,
            $this->request->group_id,
            $this->request->echelon_id,
            $this->request->grade_id,
            $this->request->education_level,
            $this->request->max_age,
            $this->request->disciplinary_id,
            $this->request->target_predicate_id,
            $this->request->cpns_year,
            $this->request->grade_year,
            $this->request->credit_score,
            $this->request->competency_point,
        );

        if (isset($this->request->limit)) {
            return $this->paginateResponse(200, 'success', $users);
        } else {
            return $this->response(200, 'success', $users);
        }
    }

    /**
     * Get Compare user for Promotions
     *
     * Below is for compare user based for promotion by user id.
     * @authenticated
     * @bodyParam user_id int[] list of user_id' id. Example: [1,2]
     * @response 200 {"code":200,"message":"success","data":[{"id":527,"name":"Danang Ari Suwito","title_prefix":null,"title_suffix":"S.Sos.","employee_id_number":"198707042015031001","employee_registration_number":"180005738","echelon":{"id":7,"name":"Ahli Muda","percentage":66},"grade":{"id":7,"name":"Penata","percentage":100},"grade_effective_date":{"name":"2023-10-01","percentage":66},"cpns_effective_date":{"name":null,"percentage":0},"education_level":{"id":6,"name":"Diploma IV/Strata I","percentage":100},"notes":[{"id":2,"description":"cihuy","giver_name":"Mellinia Fitrika Irjayanti","created_at":"2024-07-04 17:50:34"}]},{"id":565,"name":"Yuyun Kusumawardani","title_prefix":null,"title_suffix":"A.Md.A.P.S.","employee_id_number":"199606032018012001","employee_registration_number":"199606032018012001","echelon":{"id":9,"name":"Pelaksana","percentage":100},"grade":{"id":10,"name":"Pengatur Tingkat I","percentage":50},"grade_effective_date":{"name":"2022-04-01","percentage":100},"cpns_effective_date":{"name":null,"percentage":0},"education_level":{"id":5,"name":"Akademik/D3/S.Muda","percentage":50},"notes":[{"id":3,"description":"ntap","giver_name":"Mellinia Fitrika Irjayanti","created_at":"2024-07-04 17:51:02"}]},{"id":570,"name":"Cindy Vandanaswari","title_prefix":null,"title_suffix":",A.Md.A.Pkt.","employee_id_number":"199905302024212005","employee_registration_number":"199905302024212005","echelon":{"id":12,"name":"Terampil","percentage":33},"grade":{"id":28,"name":"Golongan VII","percentage":100},"grade_effective_date":{"name":"2024-03-01","percentage":33},"cpns_effective_date":{"name":null,"percentage":0},"education_level":{"id":5,"name":"Akademik/D3/S.Muda","percentage":50},"notes":[]},{"id":571,"name":"Bachtiar","title_prefix":null,"title_suffix":null,"employee_id_number":"200000220","employee_registration_number":"200000220","echelon":{"id":null,"name":null,"percentage":0},"grade":{"id":null,"name":null,"percentage":0},"grade_effective_date":{"name":null,"percentage":0},"cpns_effective_date":{"name":null,"percentage":0},"education_level":{"id":null,"name":"","percentage":0},"notes":[]},{"id":1028,"name":"T. Afrizal Nur","title_prefix":null,"title_suffix":null,"employee_id_number":"TP2KAK059","employee_registration_number":"TP2KAK059","echelon":{"id":null,"name":null,"percentage":0},"grade":{"id":null,"name":null,"percentage":0},"grade_effective_date":{"name":null,"percentage":0},"cpns_effective_date":{"name":null,"percentage":0},"education_level":{"id":null,"name":"","percentage":0},"notes":[{"id":6,"description":"oh","giver_name":"Catatan 1","created_at":"2024-07-04 17:51:49"},{"id":5,"description":"Catatan 2","giver_name":"Mellinia Fitrika Irjayanti","created_at":"2024-07-04 17:51:39"},{"id":4,"description":"Catatan 3","giver_name":"Mellinia Fitrika Irjayanti","created_at":"2024-07-04 17:51:28"}]}]}
     */
    public function compare()
    {
        $messages = [
            'user_id.required' => 'User ID tidak boleh kosong.',
            'user_id.array' => 'User ID harus berupa array.',
            'user_id.min' => 'User ID minimal 2 buah.',
            'user_id.max' => 'User ID maksimal 5 buah.',
            'user_id.*.required' => 'User ID tidak boleh kosong.',
            'user_id.*.numeric' => 'User ID harus berupa angka.',
        ];

        $this->request->validate([
            'user_id' => 'required|array|min:2|max:5',
            'user_id.*' => 'required|numeric',
        ], $messages);

        $users = $this->promotionRepository->getUserByIds($this->request->user_id);
        return $this->response(200, 'success', $users);
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
            "id" => $type == 1 ? "" : $this->promotionRepository->getPositionIdByName($name),
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

    /**
     * Export user for Promotions
     *
     * Below is for export user based for promotion by user id.
     * @authenticated
     * @bodyParam user_id int[] list of user_id' id. Example: [1,2]
     */
    public function export()
    {
        $messages = [
            'user_id.required' => 'User ID tidak boleh kosong.',
            'user_id.array' => 'User ID harus berupa array.',
            'user_id.min' => 'User ID minimal 2 buah.',
            'user_id.max' => 'User ID maksimal 5 buah.',
            'user_id.*.required' => 'User ID tidak boleh kosong.',
            'user_id.*.numeric' => 'User ID harus berupa angka.',
        ];

        $this->request->validate([
            'user_id' => 'required|array|min:2|max:5',
            'user_id.*' => 'required|numeric',
        ], $messages);

        $colors = [
            '#F16637',
            '#74B856',
            '#2D9DD1',
            '#F8A232',
            '#506CB2',
            '#C22551'
        ];

        $users = $this->promotionRepository->getUserByIds($this->request->user_id);

        foreach ($users as $key => $user) {
            $user->color = $colors[$key];
        }

        $tmp = sys_get_temp_dir();

        $pdf = Pdf::loadview('exports/promotion', [
            'users' => $users,
        ]);

        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('promotion-user.pdf');
    }
}
