<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @group Profile
 *
 * APIs for user management
 */
class ProfileController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get Profile
     * @group Profile
     * @authenticated
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "nip": "0000000000000", "nrp": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
     */
    public function show()
    {

    }

    /**
     * Update Profile
     * @group Profile
     * @authenticated
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "nip": "0000000000000", "nrp": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
     */
    public function update()
    {

    }
}
