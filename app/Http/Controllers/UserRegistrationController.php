<?php

namespace App\Http\Controllers;

use App\Helpers\Responser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Repositories\PegawaiRepository;
use App\Http\Requests\UserRegistrationRequest;
use App\Repositories\UserRegistrationRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController extends Controller
{
    use Responser;

    // Repository
    protected $userRepo;
    protected $roleRepo;
    protected $pegawaiRepo;
    protected $registrationRepo;

    public function __construct(
        UserRepository $userRepo,
        RoleRepository $roleRepo,
        PegawaiRepository $pegawaiRepo,
        UserRegistrationRepository $userRegistrationRepo,
    )
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
        $this->pegawaiRepo = $pegawaiRepo;
        $this->registrationRepo = $userRegistrationRepo;
    }

    public function register(UserRegistrationRequest $request)
    {
        $username = trim($request['username']);
        $email = trim($request['email']);
        $roleId = $request['role_id'];
        $pegawaiId = $request['pegawai_id'];

        try {
            // check username apakah sudah terdaftar di table users
            if ($this->userRepo->findByUsername($username)) {
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
            if (!$this->pegawaiRepo->findById($pegawaiId)) {
                return $this->response(404, 'pegawai tidak di temukan');
            }

            // generate uuid
            $uuid = Str::uuid();

            // generate verification_key => Hash(uuid)
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

            // TODO: kirim email verification

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
