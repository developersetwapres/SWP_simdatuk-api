<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\RoleRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepo;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function list()
    {
        try {
            $roles = $this->roleRepo->list();
            if (!$roles) {
                return response()->json(
                    ResponseHelper::errResponse(404, "role is empty"),
                    404
                );
            }
        } catch (QueryException $e) {
            return response()->json(ResponseHelper::errResponse(500, 'something went wrong'), 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'errors' => null,
            'data' => $roles,
        ], 200);
    }
}
