<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionHistory\CreatePositionHistoryRequest;
use App\Http\Requests\PositionHistory\UpdatePositionHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on position history data, enabling the retrieval, creation, and updating of position history records as needed.
 */
class PositionHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Position Histories
     *
     * Retrieve the position histories.
     * @subgroup Position
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Asisten Wakil Presiden
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Perubahan Jabatan Mei ","period_month": 5,"period_year": "2024","created_at": "2024-06-07 10:22:28","total": 268}],"pagination": {"total": 292,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 30,"links": {"first_page": "http://localhost/api/position-histories?page=1","last_page": "http://localhost/api/position-histories?page=30","next_page": "http://localhost/api/position-histories?page=2","prev_page": null}}}
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

        $positionHistories = DB::table('position_histories as ph');
        $positionHistories->leftjoin('position_history_users as phu', 'ph.id', '=', 'phu.position_history_id');
        $positionHistories->select('ph.id', 'ph.name', 'ph.period_month', 'ph.period_year', 'ph.created_at', DB::raw("COUNT(phu.id) AS total"));
        $positionHistories->where('ph.name', 'like', '%' . $this->request->search . '%');
        $positionHistories->orderBy('ph.updated_at', 'desc');
        $positionHistories->orderBy('ph.created_at', 'desc');
        $positionHistories->groupby('ph.id');
        $positionHistories = $positionHistories->paginate($this->request->limit);
        if ($positionHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $positionHistories);
        }
        return $this->paginateResponse(200, 'success', $positionHistories);
    }

    /**
     * Create a New Position History
     *
     * Add a new position history entry.
     * @subgroup Position
     * @authenticated
     * @response 200 {"code": 200,"message": "Riwayat jabatan berhasil ditambah.","data": null}
     */
    public function create(CreatePositionHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $positionHistoryId = DB::table('position_histories')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    if (isset($user['decree_document']) && is_file($user['decree_document'])) {
                        $user['decree_document'] = $this->uploadDocument($user['decree_document'], 'decree_document');
                    } else {
                        $user['decree_document'] = null;
                    }
                    $user['position_history_id'] = $positionHistoryId;
                    array_push($users, $user);
                }
                DB::table('position_history_users')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Riwayat jabatan berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Position History by ID
     *
     * Retrieve position history for specific ID.
     * @subgroup Position
     * @authenticated
     * @urlParam id Refers to the ID of Position. Example: 1
     * @response 404 {"code": 404,"message": "Riwayat jabatan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"period_month": 5,"period_year": "2024","name": "Perubahan Jabatan Mei ","created_at": "2024-06-07 10:22:28","users": [{"id": 1,"user_id": 156,"name": "Mujiyono","employee_id_number": "195809031979031001","position": "Staf pada Pembantu Asisten Wakil Presiden Bidang Monitoring dan Kontrol Masyarakat, Asisten Wakil Presiden Bidang Pengawasan (th.1979-1984)","group_id": null,"group_name": null,"echelon": null,"position_status": 2,"effective_date": null,"decree": null,"decree_document": null,"decree_number": null,"type_decree_id": null,"type_decree_name": null,"type_termination_decree_id": null,"type_termination_decree_name": null,"decree_date": null,"termination_date": null,"termination_decree": null,"termination_decree_number": null,"termination_decree_date": null,"status": 0}]}}
     */
    public function show()
    {
        $positionHistory = DB::table('position_histories');
        $positionHistory->where('id', $this->request->id);
        $positionHistory->select('id', 'period_month', 'period_year', 'name', 'created_at');
        $positionHistory = $positionHistory->first();

        if (!$positionHistory) {
            return $this->response(404, 'Riwayat golongan tidak ditemukan.');
        }

        $users = DB::table('position_history_users as phu');
        $users->join('users as u', 'u.id', '=', 'phu.user_id');
        $users->leftjoin('groups as g', 'phu.group_id', '=', 'g.id');
        $users->leftjoin('decrees as tod', 'phu.type_of_decree', '=', 'tod.id');
        $users->leftjoin('decrees as totd', 'phu.type_of_termination_decree', '=', 'totd.id');
        $users->where('phu.position_history_id', $positionHistory->id);
        $users->select(
            'phu.id',
            'phu.user_id',
            'u.name',
            'u.employee_id_number',
            'phu.position',
            'g.id as group_id',
            'g.name as group_name',
            'phu.echelon',
            'phu.position_status',
            'phu.effective_date',
            'phu.decree',
            'phu.decree_document',
            'phu.decree_number',
            'tod.id as type_decree_id',
            'tod.name as type_decree_name',
            'totd.id as type_termination_decree_id',
            'totd.name as type_termination_decree_name',
            'phu.decree_date',
            'phu.termination_date',
            'phu.termination_decree',
            'phu.termination_decree_number',
            'phu.termination_decree_date',
            'phu.status'
        );
        $users = $users->get();

        foreach ($users as $user) {
            $user->decree_document = $this->getDocument($user->decree_document);
        }

        $positionHistory->users = $users;

        return $this->response(200, 'success', $positionHistory);
    }

    /**
     * Update Position History by ID
     *
     * Update an existing position history entry.
     * @subgroup Position
     * @authenticated
     * @urlParam id Refers to the ID of Position History. Example: 1
     * @response 404 {"code": 404,"message": "Riwayat jabatan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Riwayat jabatan berhasil diupdate.","data": null}
     */
    public function update(UpdatePositionHistoryRequest $request)
    {
        $positionHistory = DB::table('position_histories');
        $positionHistory->where('id', $this->request->id);
        $positionHistory->select('id');
        $positionHistory = $positionHistory->first();

        if (!$positionHistory) {
            return $this->response(404, 'Riwayat jabatan tidak ditemukan.');
        }

        $positionHistory = DB::table('position_histories');
        $positionHistory->where('id', $this->request->id);
        $positionHistory = $positionHistory->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $positionHistoryUsers = DB::table('position_history_users');
            $positionHistoryUsers->where('position_history_id', $this->request->id);
            $positionHistoryUsers->select('id');
            $positionHistoryUsers = $positionHistoryUsers->get();

            // Delete data
            $array1 = Arr::pluck($positionHistoryUsers, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('position_history_users')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                // Upload document
                if (isset($user['decree_document']) && is_file($user['decree_document'])) {
                    $user['decree_document'] = $this->uploadDocument($user['decree_document'], 'decree_document');
                }

                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('position_history_users')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['position_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('position_history_users')->insertTs($users);
            }
        }
        return $this->response(200, 'Riwayat jabatan berhasil diupdate.');
    }
}
