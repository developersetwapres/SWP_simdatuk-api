<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CreateNewRoleRequest;
use App\Http\Requests\RoleDeleteRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $roleRepo;
    protected $permissionRepo;
    protected $rolePermissionRepo;
    protected $userRepo;

    public function __construct(
        RoleRepository $roleRepo,
        PermissionRepository $permissionRepo,
        RolePermissionRepository $rolePermissionRepo,
        UserRepository $userRepo
        )
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->rolePermissionRepo = $rolePermissionRepo;
        $this->userRepo = $userRepo;
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
        // mengambil detail role menggunakan roleId dari table roles
        $roles = $this->roleRepo->roleDetail($roleId);
        if ($roles->count() == 0) {
            return response()->json(
                ResponseHelper::errResponse(404, "role tidak ditemukan"),
                404
            );
        }

        // format data response
        $result = [
            'role' => [
                'id' => $roles[0]->role_id,
                'name' => $roles[0]->role_name
            ],
            'permission' => []
        ];

        // mengambil permission dari table permissions
        $permissions = $this->permissionRepo->list();

        // mapping permissions untuk dimasukkan ke result
        foreach ($roles as $role) {
            $p = [
                'id' => $role->permission_id,
                'group' => $role->permission_group,
                'name' => $role->permission_name,
            ];

            // menyesuaikan permission action yang akan di kirim sebagai response dengan permitted_action yang ada di table permissions
            foreach ($permissions as $permission) {
                $splitStr = str_split($permission->permitted_actions);
                
                if ($permission->id == $role->permission_id) {
                    
                    foreach ($splitStr as $i) {
                        switch ($i) {
                            case 'r':
                                $action['read'] = $role->action_read;
                                break;
                            case 'c':
                                $action['create'] = $role->action_create;
                                break;
                            case 'u':
                                $action['update'] = $role->action_update;
                                break;
                            case 'd':
                                $action['delete'] = $role->action_delete;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }

            $p['action'] = $action;
            array_push($result['permission'], $p);
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

    public function updateRole(int $roleId, UpdateRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            // Check apakah role id ada di database
            $role = $this->roleRepo->findById($roleId);
            if (!$role) {
                DB::rollBack();
                return response()->json(
                    ResponseHelper::errResponse(404, 'role tidak ditemukan')
                );
            }

            // Update role name pada table roles
            if (isset($request['role_name'])) {
                $this->roleRepo->update($roleId, $request['role_name']);
            }
    
            // update role permission
            if (isset($request['permission'])) {

                foreach($request['permission'] as $permission) {
                    // check apakah id dan actions ada di permission request payload
                    if (!isset($permission['id']) || !isset($permission['actions'])) {
                        DB::rollBack();
                        return response()->json(
                            ResponseHelper::errResponse(404, 'id dan actions pada permission tidak boleh kosong')
                        );
                    }

                    $data = [
                        'role_id' => $roleId,
                        'permission_id' => $permission['id']
                    ];

                    // looping permission actions
                    foreach($permission['actions'] as $key => $value) {
                        $data[$key] = $value;
                    }

                    // update role_permissions action
                    $this->rolePermissionRepo->update($data);
                }

            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            $errorCode = $e->errorInfo[1];
            switch ($errorCode) {
                case 1062:
                    $resp = ResponseHelper::errResponse(400, 'role sudah terdaftar');
                    break;
                case 1452:
                    $resp = ResponseHelper::errResponse(404, "permission id tidak di temukan");
                    break;
                default:
                    $resp = ResponseHelper::errResponse(500, $e);
                    break;
            }

            return response()->json($resp, $resp['code']);
        }

        return response()->json(
            ResponseHelper::successResponse(200, null),
            200
        );
    }

    public function delete(int $roleId, RoleDeleteRequest $request)
    {
        try {
            $roles = $this->roleRepo->findByMultipleId([$roleId, $request['role_id']]);
            if (count($roles) < 2) {
                return response()->json(
                    ResponseHelper::errResponse(404, "role tidak di temukan"),
                    404
                );
            }

            DB::beginTransaction();

            $this->userRepo->changeRoleId($roleId, $request['role_id']);

            $this->rolePermissionRepo->deleteByRoleId($roleId);

            $this->roleRepo->delete($roleId);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(
                ResponseHelper::errResponse(500, 'something went wrong'),
                500
            );
        }
        
        return response()->json(
            ResponseHelper::successResponse(200, null),
            200
        );
    }
}
