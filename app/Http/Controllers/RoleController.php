<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Permission;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepo;
    protected $permissionRepo;

    public function __construct(RoleRepository $roleRepo, PermissionRepository $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
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

    public function detail(int $roleId)
    {
        $role = $this->roleRepo->roleDetail($roleId);
        if (!$role) {
            return response()->json(
                ResponseHelper::errResponse(404, "role tidak ditemukan"),
                404
            );
        }

        $p = $this->permissionRepo->list();

        $result = [
            'role' => [
                'id' => $role[0]->role_id,
                'name' => $role[0]->role_name
            ],
            'permission' => []
        ];

        foreach ($role as $value) {
            $permission = [
                'id' => $value['permission_id'],
                'group' => $value['permission_group'],
                'name' => $value['permission_name'],
            ];

            foreach ($p as $item) {
                if ($item['id'] == $value['permission_id']) {
                    $splitStr = str_split($item['permitted_actions']);
                    
                    foreach ($splitStr as $i) {
                        switch ($i) {
                            case 'r':
                                $action['read'] = $value['action_read'];
                                break;
                            case 'c':
                                $action['create'] = $value['action_create'];
                                break;
                            case 'u':
                                $action['update'] = $value['action_update'];
                                break;
                            case 'd':
                                $action['delete'] = $value['action_delete'];
                                break;
                            default:
                                break;
                        }
                    }
                }
            }

            $permission['action'] = $action;
            array_push($result['permission'], $permission);
        }

        return response()->json(
            ResponseHelper::successResponse(200, $result),
            200
        );
    }
}
