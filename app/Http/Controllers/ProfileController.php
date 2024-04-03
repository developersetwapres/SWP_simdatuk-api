<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "administrator","nip": "0000000000000","nrp": "0000000000000","username": "admin","email": "admin@setwapres.go.id","role_name": "administrator"}}
     */
    public function show()
    {
        $user = $this->request->user();

        $role = DB::table('roles');
        $role->where('id', $user->role_id);
        $role->select('name');
        $role = $role->first();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'nip' => $user->nip,
            'nrp' => $user->nrp,
            'foto_profil' => $user->file_foto_profil,
            'username' => $user->username,
            'email' => $user->email,
            'role_name' => $role->name,
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Update Profile
     * @group Profile
     * @authenticated
     * @response 200
     */
    public function update(UpdateProfileRequest $request)
    {

    }
}
