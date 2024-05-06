<?php

namespace App\Http\Controllers;

use App\Http\Requests\SKP\CreateSKPRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * These endpoints would allow you to track and manage the history of various activities related to employee recognition, training, and other pertinent events.
 * @subgroupDescription These endpoints allow you to perform CRUD operations on SKP data, enabling the retrieval, creation, and updating of SKP records as needed.
 */
class SKPController extends Controller
{
    protected $SKPRepository;

    public function __construct(
        Request $request,
        SKPRepository $SKPRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->SKPRepository = $SKPRepository;
    }

     /**
     * Get List of SKP
     *
     * Retrieve the history of employee SKPs.
     * @subgroup SKP
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam type integer Refers to the types of items to be displayed per page. Example: 1
     * @queryParam name string The keyword search field for the name. Example: Diklat PIM Tk.III
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-03 05:29:30","name": "Sepadya tahun 1994","period_month": 3,"period_year": "2020","start_date": "2020-10-22", "review_period": "Q1" ,"total": 2}],"pagination": {"total": 4,"count": 4,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/skp?page=1","last_page": "http://localhost/api/trainings?page=1","next_page": null,"prev_page": null}}}
     */
    public function index()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
            'type.required' => 'Tipe tidak boleh kosong.',
            'type.in' => 'Tipe harus diantara 1, 2 atau 3.',
        ];

        $validatedData = $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
            'type' => 'required|in:1,2,3',
        ], $messages);
        $this->request->limit = ($this->request->limit) ? $this->request->limit : 10;

        $skps = DB::table('skps as s');
        $skps->leftjoin('user_skps as us', 's.id', '=', 'us.skp_id');
        $skps->select('s.id', 's.created_at', 's.name', 's.period_month', 's.period_year', 's.period_review', DB::raw("COUNT(us.id) AS total"));
        $skps->where('s.name', 'like', '%' . $this->request->name . '%');
        $skps->where('s.type', $this->request->type);
        $skps->groupby('s.id');
        $skps = $skps->paginate($this->request->limit);
        if ($skps->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $trainings);
        }
        return $this->paginateResponse(200, 'success', $skps);
    }


    /**
     * Create a New SKP
     *
     * Add a new SKP entry for employees.
     * @subgroup SKP
     * @authenticated
     * @response 200 {"code": 200,"message": "Pelatihan berhasil ditambah.","data": null}
     */
    public function create(createSKPRequest $request)
    {
        try{
            DB::beginTransaction();
            $skpId = DB::table('skps')->insertGetIdTs($this->request-except('users'));

            //insert Users
            if (isset($this->request->users)){
                $users  = array();
                foreach ($this->request->users as $user){
                    $user['skp_id'] = $skpId;
                    $user['rating_work_behavior'] = $request->input('rating_work_behavior');
                    $user['employee_performance_predicate'] = $request->input('employee_performance_predicate');
                    $user['organization_performance_achievement'] = $request->input('organization_performance_achievement');
                    array_push($users, $user);
                }
                DB::table('user_skps')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'SKP berhasil ditambah.');
        }catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail SKP by ID
     *
     * Retrieve skp history for a specific employee.
     * @subgroup SKP
     * @authenticated
     * @urlParam id Refers to the ID of SKP. Example: 1
     * @response 404
     * @response 200
     */
    public function show()
    {
        $skp = DB::table('skps');
        $skp->where('id', $this->request->id);
        $skp->select('id', 'period_month', 'period_year', 'name', 'period_review', 'year');
        $skp = $skp->first();

        if (!$skp) {
            return $this->response(404, 'Pelatihan tidak ditemukan.');
        }

        $users = DB::table('skps');
        $users->where('skp_id', $skp->id);
        $users->select('id', 'rating_work_behavior', 'employee_performance_predicate', 'organization_performance_achievement', 'created_at');
        $users = $users->get();
        
        $skp->users = $users;

        return $this->response(200, 'success', $skp);
    }

    /**
     * Update SKP by ID
     *
     * Update an existing SKP entry.
     * @subgroup SKP
     * @authenticated
     * @urlParam id Refers to the ID of SKP. Example: 1
     * @response 404 {"code": 404,"message": "SKP tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "SKP berhasil diupdate.","data": null}
     */
    public function update(UpdateSKPRequest $request)
    {
        $skp = DB::table('skps');
        $skp->where('id', $this->request->id);
        $skp->select('id');
        $skp = $skp->first();

        if (!$skp) {
            return $this->response(404, 'Pelatihan tidak ditemukan.');
        }

        $skp = DB::table('skps');
        $skp->where('id', $this->request->id);
        $skp = $skp->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing user training
            $userSKPs = DB::table('user_skps');
            $userSKPs->where('skp_id', $this->request->id);
            $userSKPs->select('id');
            $userSKPs = $userSKPs->get();

            // Delete user training
            $array1 = Arr::pluck($userSKPs, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_trainings')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    $user['skp_id'] = $skpId;
                    $user['rating_work_behavior'] = $this->request->input('rating_work_behavior');
                    $user['employee_performance_predicate'] = $this->request->input('employee_performance_predicate');
                    $user['organization_performance_achievement'] = $this->request->input('organization_performance_achievement');
                    DB::table('user_trainings')->where('id', $user['id'])->updateTs($user);
                } else {

                    $user['rating_work_behavior'] = $this->request->input('rating_work_behavior');
                    $user['employee_performance_predicate'] = $this->request->input('employee_performance_predicate');
                    $user['organization_performance_achievement'] = $this->request->input('organization_performance_achievement');
                    $user['skp_id'] = $this->request->id;

                    // Collect user to bulk insert
                    array_push($users, $user);
                }
            }

            DB::table('user_skps')->insertTs($users);
        }
        return $this->response(200, 'Pelatihan berhasil diupdate.');
    }
}
