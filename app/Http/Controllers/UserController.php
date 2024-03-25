<?php

namespace App\Http\Controllers;

use App\Helpers\Responser;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\PegawaiRepository;
use App\Repositories\RoleRepository;

/**
 * @group ACL - Access Control List
 *
 * APIs for user management
 */
class UserController extends Controller
{
    use Responser;

    protected $pegawaiRepo;
    protected $roleRepo;

    public function __construct(
        PegawaiRepository $pegawaiRepo,
        RoleRepository $roleRepo) {
        $this->pegawaiRepo = $pegawaiRepo;
        $this->roleRepo = $roleRepo;
    }

    /**
     * Get List of Users
     * @group ACL - Access Control List
     * @subgroup User
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
            $user = $this->pegawaiRepo->userList();
            if (!$user) {
                return $this->response(404, 'data user tidak tersedia');
            }
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'success', $user);
    }

    /**
     * Update User by ID
     * @group ACL - Access Control List
     * @subgroup User
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @bodyParam username string New username. Example: admin123
     * @bodyParam email string New email. Example: example@domain.com
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
            $user = $this->pegawaiRepo->findUserById($userId);
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

            $this->pegawaiRepo->updateUser($userId, $data);
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response();
    }

    /**
     * Get Detail User by ID
     * @group ACL - Access Control List
     * @subgroup User
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
            $user = $this->pegawaiRepo->userDetail($userId);
            if (!$user) {
                return $this->response(404, "user dengan id: {$userId}, tidak di temukan");
            }
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'success', $user);
    }

    /**
     * Deativate User by ID
     * @group ACL - Access Control List
     * @subgroup User
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
                'role_status' => true,
            ];

            $user = $this->pegawaiRepo->findUserWithConditions($data);
            if (!$user) {
                return $this->response(404, "user dengan id: {$userId}, tidak di temukan atau status user tidak aktif");
            }

            $this->pegawaiRepo->updateUser($userId, ['role_status' => false]);
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response();
    }
}
