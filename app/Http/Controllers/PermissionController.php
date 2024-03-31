<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group ACL - Access Control List
 *
 * APIs for permissions
 */
class PermissionController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Permissions
     * @group ACL - Access Control List
     * @subgroup Permission
     * @authenticated
     * @response 200 {"code": 200,"message": "success","data": [{"id": 28,"name": "Rekapitulasi - Komposisi Pegawai","permitted_actions": "r"},{"id": 29,"name": "Rekapitulasi - Pegawai ASN","permitted_actions": "r"}]}
     */
    public function index()
    {
        $permissions = DB::table('permissions');
        $permissions->select('id', 'name', 'permitted_actions');
        $permissions = $permissions->get();
        return $this->response(200, 'success', $permissions);
    }
}
