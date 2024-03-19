<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Repositories\PegawaiRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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

    public function createNewUser(CreateUserRequest $request)
    {
        $resp = [];

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
                    $resp = ResponseHelper::errResponse(400, 'unique constraint, username already exists');
                    break;
                case 1452:
                    $resp = ResponseHelper::errResponse(404, "pegawai or role doesn't exists");
                    break;
                default:
                    $resp = ResponseHelper::errResponse(500, 'internal server error');
                    break;
            }
        }

        if ($resp) {
            return response()->json($resp, $resp['code']);
        }

        return response()->json([
            'code' => 201,
            'status' => 'created',
            'errors' => null,
            'data' => null
        ], 201);
    }

    public function userList()
    {
        try {
            $user = $this->userRepo->list();
            if (!$user) {
                return response()->json(ResponseHelper::errResponse(404, 'no record found'), 404);
            }
        } catch (QueryException $e) {
            return response()->json(ResponseHelper::errResponse(500, ''), 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'errors' => null,
            'data' => $user
        ], 201);
    }

    public function update(int $userId, UserUpdateRequest $request)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json(
                    ResponseHelper::errResponse(404, "user with id: {$userId} not found"),
                    404
                );
            }

            if ($request['username']) {
                $user->username = $request['username'];
            }
            if ($request['email']) {
                $user->email = $request['email'];
            }
            if ($request['role_id']) {
                $user->role_id = $request['role_id'];
            }
            if ($request['pegawai_id']) {
                $user->pegawai_id = $request['pegawai_id'];
            }

            $user->save();
        } catch (QueryException $e) {
            return response()->json(ResponseHelper::errResponse(500, 'something went wrong'), 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'errors' => null,
            'data' => null,
        ], 200);
    }
}
