<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateStatusRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Mail\RegisterVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * @group Access Control List
 * @subgroupDescription These endpoints allow you to perform CRUD operations on user data, enabling the retrieval, creation, updating and deleting of user records as needed.
 */
class UserController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Users
     *
     * Retrieve the user of Access Control List.
     * @subgroup User
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the username. Example: admin
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "employee_id_number": "0000000000000", "employee_registration_number": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
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

        $users = DB::table('users');
        $users->join('roles', 'users.role_id', '=', 'roles.id');
        $users->select('users.id', 'users.username', 'users.employee_id_number', 'users.employee_registration_number', 'roles.name as role_name', 'users.status');
        $users->where('users.username', 'like', '%' . $this->request->search . '%');

        if (is_null($this->request->limit)) {
            $users = $users->get();
            $message = (count($users) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->response(200, $message, $users);
        } else {
            $users = $users->paginate($this->request->limit);
            $message = ($users->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->paginateResponse(200, $message, $users);
        }
    }

    /**
     * Create a New User
     *
     * Add a new user entry for Access Control List of user.
     * @subgroup User
     * @authenticated
     * @response 422 {"code": 422, "message": "Role tidak ditemukan.", "data": null}
     * @response 422 {"code": 422, "message": "Pengguna tidak ditemukan.", "data": null}
     * @response 200 {"code": 200, "message": "Pengguna berhasil ditambah.", "data": null}
     */
    public function create(CreateUserRequest $request)
    {
        $user = DB::table('users');
        $user->select('name');
        $user->where('id', $this->request->user_id);
        $user = $user->first();
        if (!$user) {
            return $this->response(422, 'Pengguna tidak ditemukan.');
        }

        $role = DB::table('roles');
        $role->where('id', $this->request->role_id);
        $role = $role->first();
        if (!$role) {
            return $this->response(422, 'Role tidak ditemukan.');
        }

        $token = new User();
        $this->request->verification_code = $token->generateToken(true);
        $this->request->name = $user->name;

        try {
            DB::beginTransaction();

            DB::table('users')->where('id', $this->request->user_id)->updateTs([
                'username' => $this->request->username,
                'email' => $this->request->email,
                'role_id' => $this->request->role_id,
                'verification_code' => $this->request->verification_code,
                'expire_at' => date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d')))),
                'status' => true,
            ]);

            DB::commit();

            try {
                Mail::to($this->request->email)->send(new RegisterVerification($this->request));
            } catch (\Exception $e) {
                return $this->response(200, config('app.fe_url') . '/auth/new-password/' . $this->request->verification_code);
            }

            return $this->response(200, 'Pengguna berhasil ditambah.');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail User by ID
     *
     * Retrieve user for a specific Access Control List of user.
     * @subgroup User
     * @authenticated
     * @urlParam id Refers to the ID of User. Example: 1
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"username": "admin","email": "admin@setwapres.go.id","name": "administrator","employee_id_number": "0000000000000","role": {"id": 1,"name": "administrator"}}}
     * @response 404 {"code": 404,"message": "Pengguna tidak ditemukan.","data": null}
     */
    public function show()
    {
        $user = DB::table('users');
        $user->where('id', $this->request->id);
        $user->where('role_id', '!=', null);
        $user->select('role_id', 'id', 'username', 'email', 'name', 'employee_id_number', 'status');
        $user = $user->first();
        if (!$user) {
            return $this->response(404, 'Pengguna tidak ditemukan.');
        }

        $role = DB::table('roles');
        $role->where('id', $user->role_id);
        $role->select('id', 'name');
        $role = $role->first();

        unset($user->role_id);
        $user->role = $role;

        return $this->response(200, 'success', $user);
    }

    /**
     * Update User by ID
     *
     * Update an existing user entry.
     * @subgroup User
     * @authenticated
     * @urlParam id Refers to the ID of User. Example: 1
     * @bodyParam username string Refers to username being to stored. Example: administrator
     * @bodyParam email string Refers to email being to stored. Example: admin@simdatuk.go.id
     * @bodyParam role_id integer Refers to the ID of Role. Example: 1
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "employee_id_number": "0000000000000", "employee_registration_number": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
     */
    public function update(UpdateUserRequest $request)
    {
        $user = DB::table('users');
        $user->where('id', $this->request->id);
        $user->where('role_id', '!=', null);
        $user->select('role_id', 'id', 'username', 'email', 'name', 'employee_id_number');
        $user = $user->first();
        if (!$user) {
            return $this->response(404, 'Pengguna tidak ditemukan.');
        }

        $role = DB::table('roles');
        $role->where('id', $this->request->role_id);
        $role = $role->first();
        if (!$role) {
            return $this->response(422, 'Role tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            $token = new User();
            $this->request->verification_code = $token->generateToken(true);
            $this->request->name = $user->name;

            if ($user->email !== $this->request->email) {
                DB::table('users')->where('id', $this->request->id)->updateTs([
                    'email' => $this->request->email,
                    'verification_code' => $this->request->verification_code,
                    'expire_at' => date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d')))),
                ]);
            }

            $user = DB::table('users');
            $user->where('id', $this->request->id);
            $user->updateTs([
                'username' => $this->request->username,
                'role_id' => $this->request->role_id,
            ]);

            DB::commit();

            if ($user->email !== $this->request->email) {
                try {
                    Mail::to($this->request->email)->send(new RegisterVerification($this->request));
                } catch (\Exception $e) {
                    return $this->response(200, config('app.fe_url') . '/auth/new-password/' . $this->request->verification_code);
                }
            }

            return $this->response(200, 'Pengguna berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Update Status User by ID
     *
     * Update a specific user status.
     * @subgroup User
     * @authenticated
     * @response 200 {"code": 200,"message": "Pengguna berhasil diaktifkan.","data": null}
     * @response 404 {"code": 404,"message": "Mohon maaf, pengguna tidak ditemukan.","data": null}
     */
    public function status(UpdateStatusRequest $request)
    {
        $user = DB::table('users');
        $user->where('id', $this->request->id);
        $user->where('role_id', '!=', null);
        $user = $user->first();
        if (!$user) {
            return $this->response(404, 'Mohon maaf, pengguna tidak ditemukan.');
        }
        $query = DB::table('users');
        $query->where('id', $this->request->id);
        $query->updateTs([
            'status' => $this->request->status,
        ]);
        $message = ($this->request->status == true) ? 'diaktifkan' : 'dinonaktifkan';
        return $this->response(200, 'Pengguna berhasil ' . $message . '.');
    }
}
