<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CreateNewRoleRequest;
use App\Models\Permission;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $roleRepo;
    protected $permissionRepo;
    protected $rolePermissionRepo;

    public function __construct(
        RoleRepository $roleRepo,
        PermissionRepository $permissionRepo,
        RolePermissionRepository $rolePermissionRepo
        )
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->rolePermissionRepo = $rolePermissionRepo;
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

    public function createNewRole(CreateNewRoleRequest $request)
    {
        $roleName = $request['role_name'];

        try {
            DB::beginTransaction();
            
            $roleId = $this->roleRepo->save($roleName);
            
            if (isset($request['permission'])) {
                $rolePermission = [];

                foreach ($request['permission'] as $item) {
                    $rp = [
                        'permission_id' => $item['id'],
                        'role_id' => $roleId
                    ];
    
                    if (isset($item['read'])) {
                        $rp['read'] = $item['read'];
                    }
                    if (isset($item['create'])) {
                        $rp['create'] = $item['create'];
                    }
                    if (isset($item['update'])) {
                        $rp['update'] = $item['update'];
                    }
                    if (isset($item['delete'])) {
                        $rp['delete'] = $item['delete'];
                    }
    
                    array_push($rolePermission, $rp);
                }
    
                foreach ($rolePermission as $i) {
                    $this->rolePermissionRepo->save($i);
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            $resp = [];
            $errorCode = $e->errorInfo[1];
            switch ($errorCode) {
                case 1062:
                    $resp = ResponseHelper::errResponse(400, 'role sudah terdaftar');
                    break;
                case 1452:
                    $resp = ResponseHelper::errResponse(404, "permission id tidak tersedia");
                    break;
                default:
                    $resp = ResponseHelper::errResponse(500, 'internal server error');
                    break;
            }

            return response()->json($resp, $resp['code']);
        }

        return response()->json(
            ResponseHelper::successResponse(201, null),
            201
        );
    }
}
