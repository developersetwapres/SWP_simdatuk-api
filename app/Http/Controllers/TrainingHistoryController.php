<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingHistory\CreateTrainingHistoryRequest;
use App\Http\Requests\TrainingHistory\UpdateTrainingHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * These endpoints would allow you to track and manage the history of various activities related to employee recognition, training, and other pertinent events.
 * @subgroupDescription These endpoints allow you to perform CRUD operations on training data, enabling the retrieval, creation, and updating of training records as needed.
 */
class TrainingHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Trainings
     *
     * Retrieve the history of employee training sessions.
     * @subgroup Training
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam type integer Refers to the types of items to be displayed per page. Example: 1
     * @queryParam name string The keyword search field for the name. Example: Diklat PIM Tk.III
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-03 05:29:30","name": "Sepadya tahun 1994","period_month": 3,"period_year": "2020","start_date": "2020-10-22","total": 2}],"pagination": {"total": 4,"count": 4,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/trainings?page=1","last_page": "http://localhost/api/trainings?page=1","next_page": null,"prev_page": null}}}
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

        $trainings = DB::table('trainings as t');
        $trainings->leftjoin('user_trainings as ut', 't.id', '=', 'ut.training_id');
        $trainings->select('t.id', 't.created_at', 't.name', 't.period_month', 't.period_year', 't.start_date', DB::raw("COUNT(ut.id) AS total"));
        $trainings->where('t.name', 'like', '%' . $this->request->name . '%');
        $trainings->where('t.type', $this->request->type);
        $trainings->groupby('t.id');
        $trainings = $trainings->paginate($this->request->limit);
        if ($trainings->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $trainings);
        }
        return $this->paginateResponse(200, 'success', $trainings);
    }

    /**
     * Create a New Training
     *
     * Add a new training session entry for an employee.
     * @subgroup Training
     * @authenticated
     * @response 200 {"code": 200,"message": "Pelatihan berhasil ditambah.","data": null}
     */
    public function create(CreateTrainingHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $trainingId = DB::table('trainings')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    if (isset($user['certificate']) && is_file($user['certificate'])) {
                        $user['certificate'] = $this->uploadDocument($user['certificate'], 'certificate');
                    } else {
                        $user['certificate'] = null;
                    }
                    $user['training_id'] = $trainingId;
                    array_push($users, $user);
                }
                DB::table('user_trainings')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Pelatihan berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Training by ID
     *
     * Retrieve training history for a specific employee.
     * @subgroup Training
     * @authenticated
     * @urlParam id Refers to the ID of Training. Example: 1
     * @response 404
     * @response 200
     */
    public function show()
    {
        $training = DB::table('trainings');
        $training->where('id', $this->request->id);
        $training->select('id', 'period_month', 'period_year', 'name', 'reference_number', 'level', 'start_date', 'duration', 'organizer', 'link');
        $training = $training->first();

        if (!$training) {
            return $this->response(404, 'Pelatihan tidak ditemukan.');
        }

        $users = DB::table('user_trainings as ut');
        $users->join('users as u', 'u.id', '=', 'ut.user_id');
        $users->where('ut.training_id', $training->id);
        $users->select('ut.id', 'ut.user_id', 'u.name', 'u.employee_id_number', 'ut.certificate', 'ut.created_at');
        $users = $users->get();

        foreach ($users as $user) {
            $user->certificate = $this->getDocument($user->certificate);
        }

        $training->users = $users;

        return $this->response(200, 'success', $training);
    }

    /**
     * Update Training by ID
     *
     * Update an existing training session entry.
     * @subgroup Training
     * @authenticated
     * @urlParam id Refers to the ID of Training. Example: 1
     * @response 404 {"code": 404,"message": "Pelatihan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Pelatihan berhasil diupdate.","data": null}
     */
    public function update(UpdateTrainingHistoryRequest $request)
    {
        $training = DB::table('trainings');
        $training->where('id', $this->request->id);
        $training->select('id');
        $training = $training->first();

        if (!$training) {
            return $this->response(404, 'Pelatihan tidak ditemukan.');
        }

        $training = DB::table('trainings');
        $training->where('id', $this->request->id);
        $training = $training->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing user training
            $userTrainings = DB::table('user_trainings');
            $userTrainings->where('training_id', $this->request->id);
            $userTrainings->select('id');
            $userTrainings = $userTrainings->get();

            // Delete user training
            $array1 = Arr::pluck($userTrainings, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_trainings')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (isset($user['certificate']) && is_file($user['certificate'])) {
                    $user['certificate'] = $this->uploadDocument($user['certificate'], 'certificate');
                }

                if (!is_null($user['id'])) {
                    // Update existing user training
                    DB::table('user_trainings')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item user training
                    $user['training_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users > 0)) {
                DB::table('user_trainings')->insertTs($users);
            }
        }
        return $this->response(200, 'Pelatihan berhasil diupdate.');
    }
}
