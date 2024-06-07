<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeHistory\CreateGradeHistoryRequest;
use App\Http\Requests\GradeHistory\UpdateGradeHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on grade history data, enabling the retrieval, creation, and updating of grade history records as needed.
 */
class GradeHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Grade Histories
     *
     * Retrieve the grade histories.
     * @subgroup Grade
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Penata Tingkat I (III/d)
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-06-07 04:15:43","name": "Perubahan Golongan Mei ","period_month": 5,"period_year": "2024","total": 2}],"pagination": {"total": 251,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 26,"links": {"first_page": "http://localhost/api/grade-histories?page=1","last_page": "http://localhost/api/grade-histories?page=26","next_page": "http://localhost/api/grade-histories?page=2","prev_page": null}}}
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

        $gradeHistories = DB::table('grade_histories as gh');
        $gradeHistories->leftjoin('grade_history_users as ghu', 'gh.id', '=', 'ghu.grade_history_id');
        $gradeHistories->select('gh.id', 'gh.created_at', 'gh.name', 'gh.period_month', 'gh.period_year', DB::raw("COUNT(ghu.id) AS total"));
        $gradeHistories->where('gh.name', 'like', '%' . $this->request->search . '%');
        $gradeHistories->groupby('gh.id');
        $gradeHistories = $gradeHistories->paginate($this->request->limit);
        if ($gradeHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $gradeHistories);
        }
        return $this->paginateResponse(200, 'success', $gradeHistories);
    }

    /**
     * Create a New Grade History
     *
     * Add a new grade history entry.
     * @subgroup Grade
     * @authenticated
     * @response 200 {"code": 200,"message": "Riwayat golongan berhasil ditambah.","data": null}
     */
    public function create(CreateGradeHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $gradeHistoryId = DB::table('grade_histories')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['status'] = 1;
                    $user['grade_history_id'] = $gradeHistoryId;
                    array_push($users, $user);
                }
                DB::table('grade_history_users')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Riwayat golongan berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Grade History by ID
     *
     * Retrieve grade history for specific ID.
     * @subgroup Grade
     * @authenticated
     * @urlParam id Refers to the ID of Grade. Example: 1
     * @response 404 {"code": 404,"message": "Riwayat golongan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"period_month": 5,"period_year": "2024","name": "Perubahan Golongan Mei ","created_at": "2024-06-07 04:15:43","users": [{"id": 1,"user_id": 1,"name": "Mellinia Fitrika Irjayanti","employee_id_number": "00010015","grade_id": 1,"grade_name": "Pembina Utama","grade_code": "(IV/e)","effective_date": "2020-10-22","decree_number": "Nomor 50 Tahun 2008","status": 1,"created_at": "2024-06-07 04:15:43"}]}}
     */
    public function show()
    {
        $gradeHistory = DB::table('grade_histories');
        $gradeHistory->where('id', $this->request->id);
        $gradeHistory->select('id', 'period_month', 'period_year', 'name', 'created_at');
        $gradeHistory = $gradeHistory->first();

        if (!$gradeHistory) {
            return $this->response(404, 'Riwayat golongan tidak ditemukan.');
        }

        $users = DB::table('grade_history_users as ghu');
        $users->join('users as u', 'u.id', '=', 'ghu.user_id');
        $users->join('grades as g', 'ghu.grade_id', '=', 'g.id');
        $users->where('ghu.grade_history_id', $gradeHistory->id);
        $users->select(
            'ghu.id',
            'ghu.user_id',
            'u.name',
            'u.employee_id_number',
            'g.id as grade_id',
            'g.name as grade_name',
            'g.code as grade_code',
            'ghu.effective_date',
            'ghu.decree_number',
            'ghu.status',
            'ghu.created_at'
        );
        $users = $users->get();

        $gradeHistory->users = $users;

        return $this->response(200, 'success', $gradeHistory);
    }

    /**
     * Update Grade History by ID
     *
     * Update an existing grade history entry.
     * @subgroup Grade
     * @authenticated
     * @urlParam id Refers to the ID of Grade History. Example: 1
     * @response 404 {"code": 404,"message": "Riwayat golongan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Riwayat golongan berhasil diupdate.","data": null}
     */
    public function update(UpdateGradeHistoryRequest $request)
    {
        $gradeHistory = DB::table('grade_histories');
        $gradeHistory->where('id', $this->request->id);
        $gradeHistory->select('id');
        $gradeHistory = $gradeHistory->first();

        if (!$gradeHistory) {
            return $this->response(404, 'Riwayat golongan tidak ditemukan.');
        }

        $gradeHistory = DB::table('grade_histories');
        $gradeHistory->where('id', $this->request->id);
        $gradeHistory = $gradeHistory->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $gradeHistoryUsers = DB::table('grade_history_users');
            $gradeHistoryUsers->where('grade_history_id', $this->request->id);
            $gradeHistoryUsers->select('id');
            $gradeHistoryUsers = $gradeHistoryUsers->get();

            // Delete data
            $array1 = Arr::pluck($gradeHistoryUsers, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('grade_history_users')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('grade_history_users')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['grade_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('grade_history_users')->insertTs($users);
            }
        }
        return $this->response(200, 'Riwayat golongan berhasil diupdate.');
    }
}
