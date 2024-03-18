<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Repositories\PegawaiRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Helpers\ResponseHelper;
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
}
