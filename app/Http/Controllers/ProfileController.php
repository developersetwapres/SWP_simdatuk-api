<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $fotoProfil = (is_null($user->file_foto_profil)) ? asset('img/avatar.jpeg') : Storage::disk('public')->url($user->file_foto_profil);

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'nip' => $user->nip,
            'nrp' => $user->nrp,
            'foto_profil' => $fotoProfil,
            'username' => $user->username,
            'email' => $user->email,
            'role_name' => $role->name,
        ];
        return $this->response(200, 'success', $data);
    }

    /**
     * Update Profile
     *
     * Note: still bugs on elements theme when content-type is multiple/form-data, issue at https://github.com/knuckleswtf/scribe/issues/831
     *
     * @group Profile
     * @authenticated
     * @response 200 {"code": 200,"message": "Profil berhasil diupdate.","data": null}
     */
    public function update(UpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();

            DB::table('users')->where('id', $this->request->user()->id)->updateTs([
                'username' => $this->request->username,
                'email' => $this->request->email,
            ]);

            // Check if file submitted
            if ($this->request->hasFile('foto_profil')) {
                $fileExtension = '.' . $this->request->file('foto_profil')->getClientOriginalExtension();
                $fileName = Str::random(32) . $fileExtension;
                Storage::disk('public')->putFileAs('avatars/', $this->request->file('foto_profil'), $fileName);
                DB::table('users')->where('id', $this->request->user()->id)->updateTs([
                    'file_foto_profil' => 'avatars/' . $fileName,
                ]);
            }

            // Check if password submitted
            if (isset($this->request->password) && this->request->password !== null) {
                DB::table('users')->where('id', $this->request->user()->id)->updateTs([
                    'password' => Hash::make($this->request->password),
                ]);
            }

            DB::commit();
            return $this->response(200, 'Profil berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }
}
