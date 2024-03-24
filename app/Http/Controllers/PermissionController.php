<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\PermissionRepository;
use Illuminate\Database\QueryException;

/**
 * @group Permission
 *
 * APIs for permission
 */
class PermissionController extends Controller
{
    protected $permissionRepo;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * List of Permissions
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200, "message": "ok", "data": [{
     * "id": 1,
     * "group": "Rekapitulasi",
     * "name": "Komposisi Pegawai"
     * "permitted_actions": "r"
     * }]}
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     * @response 500 {"code": 500,"message": "internal server error","data": null}
     */
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

    /**
     * List of Permissions
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200, "message": "ok", "data": ["Rekapitulasi", "Data Pegawai"]}
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     * @response 500 {"code": 500,"message": "internal server error","data": null}
     */
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
