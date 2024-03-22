<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\PermissionRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionRepo;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    public function list() {
        try {
            $permissions = $this->permissionRepo->list();
            if (!$permissions) {
                return response()->json(
                    ResponseHelper::errResponse(404, 'permission tidak di temukan'),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                ResponseHelper::errResponse(500, $e),
                500
            );
        }

        return response()->json(
            ResponseHelper::successResponse(200, $permissions),
            200
        );
    }

    public function listGroup()
    {
        try {
            $permissions = $this->permissionRepo->listGroup();
            if (!$permissions) {
                return response()->json(
                    ResponseHelper::errResponse(404, 'permission tidak di temukan'),
                    404
                );
            }

            $result = [];
            foreach($permissions as $key => $value) {
                array_push($result, $key);
            }

        } catch (QueryException $e) {
            return response()->json(
                ResponseHelper::errResponse(500, $e),
                500
            );
        }

        return response()->json(
            ResponseHelper::successResponse(200, $result),
            200
        );
    }
}
