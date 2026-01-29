<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecognitionHistory\CreateRecognitionHistoryRequest;
use App\Http\Requests\RecognitionHistory\UpdateRecognitionHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on target recognition data, enabling the retrieval, creation, and updating of position history records as needed.
 */
class RecognitionHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Recognition Histories
     *
     * Retrieve the recognition histories.
     * @subgroup Recognition
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Satya Lencana
     * @response 200 {"code": 200,"message": "success","data": [{"id": 2,"created_at": "2024-06-23 04:49:42","name": "Satyalancana Karya Satya 10th","period_month": 8,"period_year": "2008","awarding_institution": null,"total": 7}],"pagination": {"total": 34,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 4,"links": {"first_page": "http://localhost/api/recognition-histories?page=1","last_page": "http://localhost/api/recognition-histories?page=4","next_page": "http://localhost/api/recognition-histories?page=2","prev_page": null}}}
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

        $recognitionHistories = DB::table('recognition_histories as rh');
        $recognitionHistories->leftjoin('recognition_history_users as rhu', 'rh.id', '=', 'rhu.recognition_history_id');
        $recognitionHistories->leftjoin('recognitions as r', 'rh.recognition_id', '=', 'r.id');
        $recognitionHistories->select('rh.id', DB::raw("DATE_FORMAT(rh.created_at, '%d-%m-%Y %H:%i:%s') as created_at"), 'r.name', 'rh.period_month', 'rh.period_year', 'rh.awarding_institution', DB::raw("COUNT(rhu.id) AS total"));
        $recognitionHistories->where('r.name', 'like', '%' . $this->request->search . '%');
        $recognitionHistories->orderBy('rh.updated_at', 'desc');
        $recognitionHistories->orderBy('rh.created_at', 'desc');
        $recognitionHistories->groupby('rh.id');
        $recognitionHistories = $recognitionHistories->paginate($this->request->limit);
        if ($recognitionHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $recognitionHistories);
        }
        return $this->paginateResponse(200, 'success', $recognitionHistories);
    }

    /**
     * Create a New Recognition History
     *
     * Add a new recognition history entry.
     * @subgroup Recognition
     * @authenticated
     * @response 200 {"code": 200,"message": "Penghargaan berhasil ditambah.","data": null}
     */
    public function create(CreateRecognitionHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $recognitionHistoryId = DB::table('recognition_histories')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['recognition_history_id'] = $recognitionHistoryId;
                    array_push($users, $user);
                }
                DB::table('recognition_history_users')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Penghargaan berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(400, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
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
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"period_month": 8,"period_year": "2008","recognition_id": 2,"recognition_name": "Satyalancana Karya Satya 20th","description": "ASN yang telah berbakti selama 20 tahun","type_of_decree": 1,"decree_date": "2008-08-17","decree_number": null,"decree_year": "2008","awarding_institution": null,"created_at": "2024-06-23 04:49:42","users": [{"id": 34,"user_id": 1428,"name": "M. Hatta Sulaiman","employee_id_number": "195709231985031001","created_at": "2024-06-23 04:49:42"}]}}
     */
    public function show()
    {
        $recognitionHistory = DB::table('recognition_histories as rh');
        $recognitionHistory->leftJoin('recognitions as r', 'rh.recognition_id', '=', 'r.id');
        $recognitionHistory->where('rh.id', $this->request->id);
        $recognitionHistory->select(
            'rh.id',
            'rh.period_month',
            'rh.period_year',
            'rh.recognition_id',
            'r.name as recognition_name',
            'rh.description',
            'rh.type_of_decree',
            'rh.decree_date',
            'rh.decree_number',
            'rh.decree_year',
            'rh.awarding_institution',
            'rh.created_at'
        );
        $recognitionHistory = $recognitionHistory->first();

        if (!$recognitionHistory) {
            return $this->response(404, 'Penghargaan tidak ditemukan.');
        }

        $users = DB::table('recognition_history_users as rhu');
        $users->join('users as u', 'u.id', '=', 'rhu.user_id');
        $users->where('rhu.recognition_history_id', $recognitionHistory->id);
        $users->select('rhu.id', 'rhu.user_id', 'u.name', 'u.employee_id_number', 'rhu.created_at');
        $users = $users->get();
        $recognitionHistory->users = $users;
        return $this->response(200, 'success', $recognitionHistory);
    }

    /**
     * Update Recognition History by ID
     *
     * Update an existing recognition history entry.
     * @subgroup Recognition
     * @authenticated
     * @urlParam id Refers to the ID of Training. Example: 1
     * @response 404 {"code": 404,"message": "Penghargaan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Penghargaan berhasil diupdate.","data": null}
     */
    public function update(UpdateRecognitionHistoryRequest $request)
    {
        $recognitionHistory = DB::table('recognition_histories');
        $recognitionHistory->where('id', $this->request->id);
        $recognitionHistory->select('id');
        $recognitionHistory = $recognitionHistory->first();

        if (!$recognitionHistory) {
            return $this->response(404, 'Penghargaan tidak ditemukan.');
        }

        $recognitionHistory = DB::table('recognition_histories');
        $recognitionHistory->where('id', $this->request->id);
        $recognitionHistory = $recognitionHistory->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $recognitionHistoryUsers = DB::table('recognition_history_users');
            $recognitionHistoryUsers->where('recognition_history_id', $this->request->id);
            $recognitionHistoryUsers->select('id');
            $recognitionHistoryUsers = $recognitionHistoryUsers->get();

            // Delete data
            $array1 = Arr::pluck($recognitionHistoryUsers, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('recognition_history_users')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (isset($user['id'])) {
                    // Update existing data
                    DB::table('recognition_history_users')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['recognition_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('recognition_history_users')->insertTs($users);
            }
        }
        return $this->response(200, 'Penghargaan berhasil diupdate.');
    }

    /**
     * Delete Recognition History by ID
     *
     * Delete a specific Recognition History.
     * @subgroup Recognition
     * @authenticated
     * @urlParam id Refers to the ID of Recognition History. Example: 1
     * @response 404 {"code": 404,"message": "Mohon maaf, riwayat penghargaan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Riwayat penghargaan berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $histories = DB::table('recognition_histories')->select('id')->where('id', $this->request->id)->first();
        if (!$histories) {
            return $this->response(404, 'Riwayat penghargaan tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            // Delete Recognition History
            DB::table('recognition_histories')->where('id', $histories->id)->delete();

            DB::commit();
            return $this->response(200, 'Riwayat penghargaan berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::warning($th);
            return $this->response(400, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }
}
