<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingHistory\CreateTrainingHistoryRequest;
use App\Http\Requests\TrainingHistory\UpdateTrainingHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @group History
 * These endpoints would allow you to track and manage the history of various activities related to employee recognition, training, and other pertinent events.
 * @subgroupDescription These endpoints allow you to perform CRUD operations on training history data, enabling the retrieval, creation, and updating of position history records as needed.
 */
class TrainingHistoryController extends Controller
{
    protected $request;
    protected $posted;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Training Histories
     *
     * Retrieve the training histories.
     * @subgroup Training
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam type integer Refers to the types of items to be displayed per page. Example: 1
     * @queryParam search string The keyword search field for the level_name. Example: Sepadya tahun 1994
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-03 05:29:30","level_name": "Sepadya tahun 1994","period_month": 3,"period_year": "2020","start_date": "2020-10-22","total": 2}],"pagination": {"total": 4,"count": 4,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/trainings?page=1","last_page": "http://localhost/api/trainings?page=1","next_page": null,"prev_page": null}}}
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

        $trainingHistories = DB::table('training_histories as th');
        $trainingHistories->leftjoin('training_history_users as thu', 'th.id', '=', 'thu.training_history_id');
        $trainingHistories->select(
            'th.id',
            DB::raw("DATE_FORMAT(th.created_at, '%d-%m-%Y %H:%i:%s') as created_at"),
            'th.name',
            'th.period_month',
            'th.period_year',
            'th.start_date',
            'th.end_date',
            DB::raw("COUNT(thu.id) AS total")
        );
        $trainingHistories->where('th.name', 'like', '%' . $this->request->search . '%');
        $trainingHistories->where('th.type', $this->request->type);
        $trainingHistories->orderBy('th.updated_at', 'desc');
        $trainingHistories->orderBy('th.created_at', 'desc');
        $trainingHistories->groupby('th.id');
        $trainingHistories = $trainingHistories->paginate($this->request->limit);
        if ($trainingHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $trainingHistories);
        }
        return $this->paginateResponse(200, 'success', $trainingHistories);
    }

    /**
     * Create a New Training History
     *
     * Add a new training history entry.
     * @subgroup Training
     * @authenticated
     * @response 200 {"code": 200,"message": "Pelatihan berhasil ditambah.","data": null}
     */
    public function create(CreateTrainingHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $trainingHistoryId = DB::table('training_histories')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    if (isset($user['certificate']) && is_file($user['certificate'])) {
                        $user['certificate'] = $this->uploadDocument($user['certificate'], 'certificate');
                    } else {
                        $user['certificate'] = null;
                    }
                    $user['training_history_id'] = $trainingHistoryId;
                    array_push($users, $user);
                }
                DB::table('training_history_users')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Pelatihan berhasil ditambah.');
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(400, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Training History by ID
     *
     * Retrieve training history for specific ID.
     * @subgroup Training
     * @authenticated
     * @urlParam id Refers to the ID of Training History. Example: 1
     * @response 404 {"code": 404,"message": "Pelatihan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 14800,"period_month": 3,"period_year": "2020","name": "Sepadya tahun 1994","reference_number": "13936/PPKASN/09/2021","level": "Diklat PIM Tk.III","start_date": "2020-10-22","duration": 10,"organizer": "PPKASN","link": "https://google.com","users": [{"id": 15578,"user_id": 9069,"name": "Stanislaus Widjanarto","employee_id_number": "020002268","certificate": null,"created_at": "2024-06-21 11:09:38"}]}}
     */
    public function show()
    {
        $checkTraining = DB::table('training_histories')->find($this->request->id);
        if (!$checkTraining) {
            return $this->response(404, 'Pelatihan tidak ditemukan.');
        }

        $trainingHistory = DB::table('training_histories as th')->where('th.id', $this->request->id);
        $trainingHistory->select(
            'th.id',
            'th.period_month',
            'th.period_year',
            'th.name',
            'th.reference_number',
            'th.type',
            'th.start_date',
            'th.end_date',
            'th.duration',
            'th.organizer',
            'th.link',
            'th.description',
            'th.level',
            'th.group_id',
        );
        if ($trainingHistory->first()->type == 3 && !is_null($trainingHistory->first()->group_id)) {
            $trainingHistory->join('groups as rumpun', 'rumpun.id', '=', 'th.group_id');
            $trainingHistory->selectRaw('rumpun.name as group_name, NULL as `level_name`');
        } elseif ($trainingHistory->first()->type == (1 || 2) && !is_null($trainingHistory->first()->level)) {
            $trainingHistory->join('training_levels as jenjang', 'th.level', '=', 'jenjang.id');
            $trainingHistory->selectRaw('jenjang.level_name, NULL as `group_name`');
        } else {
            $trainingHistory->selectRaw('NULL as `level_name`, NULL as `group_name`');
        }
        $trainingHistory = $trainingHistory->first();

        $trainingHistory->type = ($trainingHistory->type == 1 ? 'Pelatihan Struktural' : ($trainingHistory->type == 2 ? 'Pelatihan Fungsional' : ($trainingHistory->type == 3 ? 'Pelatihan Teknis' : NULL)));;

        $users = DB::table('training_history_users as thu');
        $users->join('users as u', 'u.id', '=', 'thu.user_id');
        $users->where('thu.training_history_id', $trainingHistory->id);
        $users->select('thu.id', 'thu.user_id', DB::raw("CONCAT(COALESCE(u.title_prefix,''),' ',u.name,' ',COALESCE(u.title_suffix,'')) as name"), 'u.employee_id_number', 'thu.certificate', 'thu.created_at');
        $users = $users->get();

        foreach ($users as $user) {
            $user->certificate = $this->getDocument($user->certificate);
        }
        $trainingHistory->users = $users;
        return $this->response(200, 'success', $trainingHistory);
    }

    /**
     * Update Training History by ID
     *
     * Update an existing training history entry.
     * @subgroup Training
     * @authenticated
     * @urlParam id Refers to the ID of Training History. Example: 1
     * @response 404 {"code": 404,"message": "Pelatihan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Pelatihan berhasil diupdate.","data": null}
     */
    public function update(UpdateTrainingHistoryRequest $request)
    {
        try {
            $trainingHistory = DB::table('training_histories');
            $trainingHistory->where('id', $this->request->id);
            $trainingHistory->select('id');
            $trainingHistory = $trainingHistory->first();

            if (!$trainingHistory) {
                return $this->response(404, 'Pelatihan tidak ditemukan.');
            }

            $trainingHistory = DB::table('training_histories');
            $trainingHistory->where('id', $this->request->id);
            $trainingHistory = $trainingHistory->updateTs($this->request->except('users'));

            $users = array();

            if (isset($this->request->users)) {

                // Get existing data
                $trainingHistoryUsers = DB::table('training_history_users');
                $trainingHistoryUsers->where('training_history_id', $this->request->id);
                $trainingHistoryUsers->select('id');
                $trainingHistoryUsers = $trainingHistoryUsers->get();

                // Delete data
                $array1 = Arr::pluck($trainingHistoryUsers, 'id');
                $array2 = Arr::pluck($this->request->users, 'id');
                $result = array_diff($array1, $array2);
                DB::table('training_history_users')->whereIn('id', $result)->delete();

                foreach ($this->request->users as $user) {
                    // Upload Document
                    if (isset($user['certificate']) && is_file($user['certificate'])) {
                        $user['certificate'] = $this->uploadDocument($user['certificate'], 'certificate');
                    } else if ($user['delete_certificate'] == true) {
                        $user['certificate'] = null;
                    } else {
                        unset($user['certificate']);
                    }
                    unset($user['delete_certificate']);

                    if (!is_null($user['id'])) {
                        // Update existing data
                        DB::table('training_history_users')->where('id', $user['id'])->updateTs($user);
                    } else {
                        // Insert new item
                        $user['training_history_id'] = $this->request->id;
                        array_push($users, $user);
                    }
                }
                if (count($users) > 0) {
                    DB::table('training_history_users')->insertTs($users);
                }
            }
            return $this->response(200, 'Pelatihan berhasil diupdate.');
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(400, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Delete Training History by ID
     *
     * Delete a specific Training History.
     * @subgroup Training
     * @authenticated
     * @urlParam id Refers to the ID of Training History. Example: 1
     * @response 404 {"code": 404,"message": "Mohon maaf, riwayat pelatihan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Riwayat pelatihan berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $histories = DB::table('training_histories')->select('id')->where('id', $this->request->id)->first();
        if (!$histories) {
            return $this->response(404, 'Riwayat pelatihan tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            // Delete Training History
            DB::table('training_histories')->where('id', $histories->id)->delete();

            DB::commit();
            return $this->response(200, 'Riwayat pelatihan berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::warning($th);
            return $this->response(400, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get List of Training Structural Levels
     *
     * Retrieve the Level of master data.
     * @subgroup Level
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Struktural
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"level_name": "Struktural", "level_type": "Jenjang Struktural", "description": "-"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/training-histories/levels/structural?page=1","last_page": "http://localhost/api/training-histories/levels/structural?page=32","next_page": "http://localhost/api/training-histories/levels/structural?page=2","prev_page": null}}}
     *
     */
    public function structuralLevels()
    {
        $messages = [
            'page.numeric'  => 'Page harus berupa angka.',
            'page.min'      => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min'     => 'Limit minimal harus 1 atau lebih.',
        ];

        $validatedData = $this->request->validate([
            'page'  => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
        ], $messages);

        $levels = DB::table('training_levels');
        $levels->select('training_levels.id', 'training_levels.level_name', 'training_levels.level_type', 'training_levels.description');
        $levels->where('training_levels.level_name', 'like', '%' . $this->request->search . '%');
        $levels->where('training_levels.level_type', '=', 1); // jenjang struktural
        $levels->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $levels = $levels->get();
            $message = (count($levels) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($levels as $key => $item) {
                $item->level_type = ($item->level_type == 1) ? 'Jenjang Struktural' : 'Jenjang Fungsional';
            }
            return $this->response(200, $message, $levels);
        } else {
            $levels = $levels->paginate($this->request->limit);
            $message = ($levels->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($levels as $key => $item) {
                $item->level_type = ($item->level_type == 1) ? 'Jenjang Struktural' : 'Jenjang Fungsional';
            }
            return $this->paginateResponse(200, $message, $levels);
        }
    }

    /**
     * Get List of Training Functional Levels
     *
     * Retrieve the Level of master data.
     * @subgroup Level
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Fungsional
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"level_name": "Fungsional", "level_name": "Jenjang Fungsional", "description": "-"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/training-histories/levels/functional?page=1","last_page": "http://localhost/api/training-histories/levels/functional?page=32","next_page": "http://localhost/api/training-histories/levels/functional?page=2","prev_page": null}}}
     *
     */
    public function functionalLevels()
    {
        $messages = [
            'page.numeric'  => 'Page harus berupa angka.',
            'page.min'      => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min'     => 'Limit minimal harus 1 atau lebih.',
        ];

        $validatedData = $this->request->validate([
            'page'  => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
        ], $messages);

        $levels = DB::table('training_levels');
        $levels->select('training_levels.id', 'training_levels.level_name', 'training_levels.level_type', 'training_levels.description');
        $levels->where('training_levels.level_name', 'like', '%' . $this->request->search . '%');
        $levels->where('training_levels.level_type', '=', 2); // jenjang fungsional
        $levels->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $levels = $levels->get();
            $message = (count($levels) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($levels as $key => $item) {
                $item->level_type = ($item->level_type == 1) ? 'Jenjang Struktural' : 'Jenjang Fungsional';
            }
            return $this->response(200, $message, $levels);
        } else {
            $levels = $levels->paginate($this->request->limit);
            $message = ($levels->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($levels as $key => $item) {
                $item->level_type = ($item->level_type == 1) ? 'Jenjang Struktural' : 'Jenjang Fungsional';
            }
            return $this->paginateResponse(200, $message, $levels);
        }
    }

    /**
     * Get List of Training Technical Groups
     *
     * Retrieve the Level of master data.
     * @subgroup Level
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Tata Usaha
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Tata Usaha", "type": "Rumpun Pelatihan Teknis"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/training-histories/groups?page=1","last_page": "http://localhost/api/training-histories/groups?page=32","next_page": "http://localhost/api/training-histories/groups?page=2","prev_page": null}}}
     *
     */
    public function technicalGroups()
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

        $groups = DB::table('groups');
        $groups->select('id', 'name', 'type', 'created_at');
        $groups->where('name', 'like', '%' . $this->request->search . '%');
        $groups->where('type', '=', 2); // 1=Rumpun Pelatihan Teknis
        $groups->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $groups = $groups->get();
            $message = (count($groups) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($groups as $key => $item) {
                $item->type = ($item->type == 1) ? 'Rumpun Riwayat Pegawai' : 'Rumpun Pelatihan Teknis';
            }
            return $this->response(200, $message, $groups);
        } else {
            $groups = $groups->paginate($this->request->limit);
            $message = ($groups->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($groups as $key => $item) {
                $item->type = ($item->type == 1) ? 'Rumpun Riwayat Pegawai' : 'Rumpun Pelatihan Teknis';
            }
            return $this->paginateResponse(200, $message, $groups);
        }
    }
}
