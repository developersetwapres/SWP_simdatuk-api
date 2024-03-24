<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateUserRequest;
use App\Repositories\PegawaiRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Helpers\ResponseHelper;
use App\Helpers\Responser;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

/**
 * @group User
 *
 * APIs for user management
 */
class UserController extends Controller
{
    use Responser;

    protected $userRepo;
    protected $pegawaiRepo;
    protected $roleRepo;

    protected $userService;

    public function __construct(
        UserRepository $userRepo,
        PegawaiRepository $pegawaiRepo,
        RoleRepository $roleRepo)
    {
        $this->userRepo = $userRepo;
        $this->pegawaiRepo = $pegawaiRepo;
        $this->roleRepo = $roleRepo;
    }

    /**
     * Create new User
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @bodyParam username string required username for user login. Example: admin123
     * @bodyParam password string required password for user login. Example: password
     * @bodyParam email string required email untuk mengirim verifikasi. Example: example@domain.com
     * @bodyParam pegawai_id integer required id pegawai. Example: 1
     * @bodyParam role_id integer required role id. Example: 1
     * @response 201 {"code": 201,"message": "created","data": null}
     * @response 400 {"code": 400,"message": "Password tidak boleh kosong","data": null}
     * @response 404 {"code": 404,"message": "tidak ada data","data": null}
     * @response 500 {"code": 500,"message": "something went wrong","data": null}
     */
    public function createNewUser(CreateUserRequest $request)
    {
        try {
            $this->userRepo->save([
                'pegawai_id' => $request['pegawai_id'],
                'username' => $request['username'],
                'password' => Hash::make($request['password']),
                'email' => $request['email'],
                'role_id' => $request['role_id'],
            ]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            switch ($errorCode) {
                case 1062:
                    return $this->response(400, 'username sudah terdaftar');
                case 1452:
                    return $this->response(404, "pegawai atau role tidak tersedia");
                default:
                    return $this->internalServerErrorResponse();
            }
        }

        return $this->response(201, 'created');
    }

    /**
     * List of Users
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200,"message": "ok","data": [{
     * "id": 1,
     * "username":
     * "admin",
     * "password": "voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e",
     * "nip": "example123",
     * "nrp": "123example",
     * "name": "admin",
     * "status": 1
     * }]}
     * @response 404 {"code": 404,"message": "tidak ada data","data": null}
     * @response 500 {"code": 500,"message": "internal server error","data": null}
     */
    public function userList()
    {
        try {
            $user = $this->userRepo->list();
            if (!$user) {
                return $this->response(404, 'data user tidak tersedia');
            }
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'success', $user);
    }

    /**
     * Update
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @bodyParam username string New username. Example: admin123
     * @bodyParam email string New email. Example: example@domain.com
     * @bodyParam pegawai_id integer Pegawai ID. Example: 1
     * @bodyParam role_id integer Role ID. Example: 1
     * @response 200 {"code": 200,"message": "ok", "data": null}
     * @response 400 {"code": 400,"message": "bad request", "data": null}
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     */
    public function update(int $userId, UserUpdateRequest $request)
    {
        try {
            $user = $this->userRepo->findById($userId);
            if (!$user) {
                return $this->response(404, "user dengan id: {$userId}, tidak ditemukan");
            }

            $data = [];
            if ($request['username']) {
                $data['username'] = $request['username'];
            }
            if ($request['email']) {
                $data['email'] = $request['email'];
            }
            if ($request['role_id']) {
                $data['role_id'] = $request['role_id'];
            }
            if ($request['pegawai_id']) {
                $data['pegawai_id'] = $request['pegawai_id'];
            }

            $this->userRepo->update($userId, $data);
        } catch (QueryException $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response();
    }

    /**
     * User Detail
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200,"message": "ok", "data": {
     *  "id": 1,
     *  "username": "admin",
     *  "password": "password",
     *  "email": "example@domain.com",
     *  "nip": "nip123",
     *  "nrp": "nrp123",
     *  "role_name": "administrator"
     *  }
     * }
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     * @response 500 {"code": 500,"message": "internal server error", "data": null}
     */
    public function userDetail(int $userId)
    {
        try {
            $user = $this->userRepo->userDetail($userId);
            if (!$user) {
                return $this->response(404, "user dengan id: {$userId}, tidak di temukan");
            }
        } catch (QueryException $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'success', $user);
    }

    /**
     * User Deactivate
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200,"message": "ok", "data": null}
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     * @response 500 {"code": 500,"message": "internal server error", "data": null}
     */
    public function deactivate(int $userId)
    {
        try {
            $data = [
                'id' => $userId,
                'status' => true
            ];

            $user = $this->userRepo->findWithConditions($data);
            if (!$user) {
                return $this->response(404, "user dengan id: {$userId}, tidak di temukan atau status tidak aktif");
            }

            $this->userRepo->update($userId, ['status' => false]);

        } catch (QueryException $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response();
    }
}
