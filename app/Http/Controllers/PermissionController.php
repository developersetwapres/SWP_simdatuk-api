<?php

namespace App\Http\Controllers;

use App\Helpers\Responser;
use App\Repositories\PermissionRepository;
use Illuminate\Database\QueryException;

/**
 * @group Permission
 *
 * APIs for permission
 */
class PermissionController extends Controller
{
    use Responser;

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
                return $this->response(404, 'permission tidak di temukan');
            }

        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'ok', $permissions);
    }

    /**
     * Permissions Group
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
                return $this->response(404, 'permission group tidak ditemukan');
            }

            // mengambil hanya data group
            $result = [];
            foreach($permissions as $key => $value) {
                array_push($result, $key);
            }

        } catch (QueryException $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'ok', $result);
    }
}
