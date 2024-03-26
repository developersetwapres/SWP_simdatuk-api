<?php

namespace App\Http\Controllers;

use App\Helpers\Responser;
use Illuminate\Support\Str;
use App\Repositories\RoleRepository;
use App\Repositories\PegawaiRepository;
use App\Http\Requests\UserRegistrationRequest;
use App\Mail\UserRegisterVerification;
use App\Repositories\UserRegistrationRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * @group ACL - Access Control List
 *
 * APIs for user management
 */
class UserRegistrationController extends Controller
{
    use Responser;

    // Repository
    protected $roleRepo;
    protected $pegawaiRepo;
    protected $registrationRepo;

    public function __construct(
        RoleRepository $roleRepo,
        PegawaiRepository $pegawaiRepo,
        UserRegistrationRepository $userRegistrationRepo,
    )
    {
        $this->roleRepo = $roleRepo;
        $this->pegawaiRepo = $pegawaiRepo;
        $this->registrationRepo = $userRegistrationRepo;
    }

    /**
     * Register new User by Admin
     * @group ACL - Access Control List
     * @subgroup Register
     * @bodyParam username string New username. Example: admin123
     * @bodyParam email string New email. Example: example@domain.com
     * @bodyParam role_id integer Role ID. Example: 1
     * @bodyParam pegawai_id integer Pegawai ID. Example: 1
     * @response 201 {"code": 201,"message": "created","data": {
     * "username":"admin123",
     * "email":"example@domain.com",
     * "verification_key": "voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e",
     * "expired_at": "2017-07-21 17:32:28",
     * }}
     * @response 400 {"code": 400, "message": "bad request", "data": null}
     * @response 403 {"code": 403, "message": "forbidden", "data": null}
     * @response 404 {"code": 404, "message": "not found", "data": null}
     * @response 500 {"code": 500, "message": "internal server error", "data": null}
     */
    public function register(UserRegistrationRequest $request)
    {
        $username = trim($request['username']);
        $email = trim($request['email']);
        $roleId = $request['role_id'];
        $pegawaiId = $request['pegawai_id'];

        try {
            // check username apakah sudah terdaftar di table pegawai
            if ($this->pegawaiRepo->findUserWithConditions(['username' => $username])) {
                return $this->response(400, 'username sudah terdaftar');
            }

            // check apakah username sudah  terdaftar di table user_registrations
            if ($this->registrationRepo->findByUsername($username)) {
                return $this->response(400, 'username sudah terdaftar dan menunggu verifikasi');
            }

            // validasi role_id
            if (!$this->roleRepo->findById($roleId)) {
                return $this->response(404, 'role tidak di temukan');
            }

            // validasi pegawai_id
            $pegawai = $this->pegawaiRepo->findById($pegawaiId);
            if (!$pegawai) {
                return $this->response(404, 'pegawai tidak di temukan');
            }
            if ($pegawai->role_id !== null) {
                return $this->response(400, "{$pegawai->nama}, sudah terdaftar sebagai user");
            }

            // generate uuid
            $uuid = Str::uuid();

            // generate verification_key
            $key = Hash::make($uuid);

            // buat expired_at
            $expired = Carbon::now('Asia/Jakarta')->addHours(4);

            // menyiapkan data yang akan di simpan
            $data = [
                'id' => $uuid,
                'role_id' => $roleId,
                'pegawai_id' => $pegawaiId,
                'email' => $email,
                'username' => $username,
                'verification_key' => $key,
                'expired_at' => $expired,
            ];

            // simpan ke table user_registrations
            $this->registrationRepo->save($data);

            // kirim email verifikasi
            $data['nama'] = ucwords($pegawai->nama);
            $data['base_url'] = env('FE_BASE_URL') . '/set-password';
            Mail::to($email)
                ->send(new UserRegisterVerification($data));

        } catch (\Exception $e) {
            return $this->internalServerErrorResponse($e->getMessage());
        }

        $resp = [
            'username' => $username,
            'email' => $email,
            'verification_key' => $key,
            'expired_at' => $expired->format('Y-m-d H:i:s')
        ];

        return $this->response(201, 'created', $resp);
    }
}
