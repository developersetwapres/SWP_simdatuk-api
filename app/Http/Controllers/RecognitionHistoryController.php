<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecognitionHistory\CreateRecognitionHistoryRequest;
use App\Http\Requests\RecognitionHistory\UpdateRecognitionHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on recognition data, enabling the retrieval, creation, and updating of recognition records as needed.
 */
class RecognitionHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Recognitions
     *
     * Retrieve the history of employee recognitions.
     * @subgroup Recognition
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam name string The keyword search field for the name. Example: Satya Lencana
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-05 11:14:44","name": "Diklat Komputer Microsoft Excell","period_month": 3,"period_year": "2020","awarding_institution": "Setwapres","total": 1}],"pagination": {"total": 2,"count": 2,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/recognitions?page=1","last_page": "http://localhost/api/recognitions?page=1","next_page": null,"prev_page": null}}}
     */
    public function index()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
        ];

        $validatedData = $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
        ], $messages);
        $this->request->limit = ($this->request->limit) ? $this->request->limit : 10;

        $recognitions = DB::table('recognitions as r');
        $recognitions->leftjoin('user_recognitions as ur', 'r.id', '=', 'ur.recognition_id');
        $recognitions->select('r.id', 'r.created_at', 'r.name', 'r.period_month', 'r.period_year', 'r.awarding_institution', DB::raw("COUNT(ur.id) AS total"));
        $recognitions->where('r.name', 'like', '%' . $this->request->name . '%');
        $recognitions->groupby('r.id');
        $recognitions = $recognitions->paginate($this->request->limit);
        if ($recognitions->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $recognitions);
        }
        return $this->paginateResponse(200, 'success', $recognitions);
    }

    /**
     * Create a New Recognition
     *
     * Add a new recognition entry for an employee.
     * @subgroup Recognition
     * @authenticated
     * @response 200 {"code": 200,"message": "Penghargaan berhasil ditambah.","data": null}
     */
    public function create(CreateRecognitionHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $recognitionId = DB::table('recognitions')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['recognition_id'] = $recognitionId;
                    array_push($users, $user);
                }
                DB::table('user_recognitions')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Penghargaan berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Recognition by ID
     *
     * Retrieve recognition history for a specific employee.
     * @subgroup Recognition
     * @authenticated
     * @urlParam id Refers to the ID of Recognition. Example: 1
     * @response 404 {"code": 404,"message": "Penghargaan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 2,"period_month": 3,"period_year": "2020","name": "Diklat Komputer Microsoft Excell","description": "Excel","type_of_decree": 1,"decree_date": "2020-10-22","decree_number": "Keppres Nomor 031/TK/tahun 2008, 17-Aug-08","decree_year": "2020","awarding_institution": "Setwapres","date_of_receipt": "2020-10-22","created_at": "2024-05-05 11:16:15","users": [{"id": 2,"name": "Umi Yance Puspita"},{"id": 3,"name": "Digdaya Ardianto"}]}}
     */
    public function show()
    {
        $recognition = DB::table('recognitions');
        $recognition->where('id', $this->request->id);
        $recognition->select('id', 'period_month', 'period_year', 'name', 'description', 'type_of_decree', 'decree_date', 'decree_number', 'decree_year', 'awarding_institution', 'date_of_receipt', 'created_at');
        $recognition = $recognition->first();

        if (!$recognition) {
            return $this->response(404, 'Penghargaan tidak ditemukan.');
        }

        $users = DB::table('user_recognitions as ur');
        $users->join('users as u', 'u.id', '=', 'ur.user_id');
        $users->where('ur.recognition_id', $recognition->id);
        $users->select('ur.id', 'ur.user_id', 'u.name', 'ur.created_at', 'u.employee_id_number');
        $users = $users->get();

        $recognition->users = $users;

        return $this->response(200, 'success', $recognition);
    }

    /**
     * Update Recognition by ID
     *
     * Update an existing recognition entry.
     * @subgroup Recognition
     * @authenticated
     * @urlParam id Refers to the ID of Training. Example: 1
     * @response 404 {"code": 404,"message": "Penghargaan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Penghargaan berhasil diupdate.","data": null}
     */
    public function update(UpdateRecognitionHistoryRequest $request)
    {
        $recognition = DB::table('recognitions');
        $recognition->where('id', $this->request->id);
        $recognition->select('id');
        $recognition = $recognition->first();

        if (!$recognition) {
            return $this->response(404, 'Penghargaan tidak ditemukan.');
        }

        $recognition = DB::table('recognitions');
        $recognition->where('id', $this->request->id);
        $recognition = $recognition->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $userRecognitions = DB::table('user_recognitions');
            $userRecognitions->where('recognition_id', $this->request->id);
            $userRecognitions->select('id');
            $userRecognitions = $userRecognitions->get();

            // Delete data
            $array1 = Arr::pluck($userRecognitions, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_recognitions')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('user_recognitions')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['recognition_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('user_recognitions')->insertTs($users);
            }
        }
        return $this->response(200, 'Penghargaan berhasil diupdate.');
    }
}
