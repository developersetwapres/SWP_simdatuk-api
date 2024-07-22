<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CodeVerificationRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * @group Authentication
 * Below is a list of endpoints crucial for authentication processes, including login, forgot password, token verification, reset password, and logout, facilitating secure and authenticated access for users.
 */
class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Login
     *
     * Below are the endpoints specifically tailored for the login process, utilizing Sanctum as the authentication method, ensuring secure access for users.
     * @response 200 {"code": 200,"message": "Pengguna berhasil login.","token": "12|Qt9HeTzHAmw5s2fLiRO09eovw1yF3EcSHFR4mU9Ga3cc24ab","user": {"id": 1,"email": "admin@setwapres.go.id","username": "admin","photo_profile": null,"employee_id_number": "0000000000000","employee_registration_number": "0000000000000","role": {"id": 1,"name": "administrator"},"permissions": [{"id": 8,"name": "Data Pegawai - ASN","create": 1,"read": 1,"update": 1,"delete": 0}]}}
     * @response 422 {"code": 422,"message": "Username tidak boleh kosong.","data": {"username": ["Username email tidak boleh kosong."], "password": ["Kata sandi tidak boleh kosong."]}}
     * @response 401 {"code": 401,"message": "Password yang anda masukkan salah.","data": null}
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $this->request->username)->first();

        if (!$user || !Hash::check($this->request->password, $user->password)) {
            return $this->response(401, 'Password yang anda masukkan salah.');
        } else if (is_null($user->role_id)) {
            return $this->response(401, 'Anda tidak terdaftar sebagai pengguna, silakan hubungi tim IT.');
        } else if ($user->status != true) {
            return $this->response(401, 'Status pengguna tidak aktif.');
        } else if (!is_null($user->verification_code)) {
            return $this->response(401, 'Email belum terverifikasi.');
        } else {
            $token = $user->createToken('web')->plainTextToken;
        }

        $user = DB::table('users');
        $user->select('users.id', 'users.email', 'users.username', 'users.photo_profile', 'users.employee_id_number', 'users.employee_registration_number');
        $user->where('username', $this->request->username);
        $user = $user->first();
        $user->photo_profile = $this->getDocument($user->photo_profile, true);

        $role = DB::table('roles');
        $role->join('users', 'roles.id', 'users.role_id');
        $role->select('roles.id', 'roles.name');
        $role->where('users.id', $user->id);
        $role = $role->first();

        $user->role = $role;

        $permissions = DB::table('permissions as p');
        $permissions->join('role_permissions as rp', 'p.id', 'rp.permission_id');
        $permissions->join('roles as r', 'rp.role_id', 'r.id');
        $permissions->where('rp.role_id', $role->id);
        $permissions->select('p.id', 'p.name', 'rp.create', 'rp.read', 'rp.update', 'rp.delete');
        $permissions = $permissions->get();

        $user->permissions = $permissions;

        return response()->json([
            'code' => 200,
            "message" => "Pengguna berhasil login.",
            "token" => $token,
            "user" => $user,
        ], 200);
    }

    /**
     * Forgot Password
     *
     * Below are the endpoints dedicated to the 'forgot password' functionality, facilitating the secure retrieval and resetting of passwords for users who have forgotten them.
     * @response 200 {"code": 200,"message": "Email sudah dikirim.","data": null}
     * @response 422 {"code": 422,"message": "Email tidak boleh kosong.","data": null}
     * @response 404 {"code": 404,"message": "Email tidak terdaftar sebagai pengguna.","data": null}
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = DB::table('users');
        $user->select('role_id');
        $user->where('email', $this->request->email);
        $user = $user->first();
        if (is_null($user->role_id)) {
            return $this->response(404, 'Email tidak terdaftar sebagai pengguna.');
        }

        // Generete Token
        $token = new User();
        $this->request->verification_code = $token->generateToken(false);

        DB::table('password_reset_tokens')->insert([
            'email' => $this->request->email,
            'verification_code' => $this->request->verification_code,
            'expire_at' => date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d')))),
        ]);

        // Send Email
        Mail::to($this->request->email)->send(new ForgotPassword($this->request));

        return $this->response(200, 'Email sudah dikirim.');
    }

    /**
     * Code Verification
     *
     * Below are the endpoints designated for token verification during both registration and password recovery processes, ensuring secure validation of user actions.
     * @response 200 {"code": 200,"message": "Verifikasi kode berhasil.", "data": null}
     * @response 404 {"code": 404,"message": "Verifikasi kode tidak tersedia.","data": null}
     * @response 404 {"code": 404,"message": "Verifikasi kode sudah kadaluarsa.","data": null}
     */
    public function codeVerification(CodeVerificationRequest $request)
    {
        return $this->codeValidation();
    }

    /**
     * Reset Password
     *
     * Below are the endpoints designed for resetting passwords or setting new passwords after token verification, providing a secure mechanism for users to regain access to their accounts.
     * @response 200 {"code": 200,"message": "Reset password berhasil disimpan","data": null}
     * @response 200 {"code": 200,"message": "Password baru berhasil disimpan.","data": null}
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $codeValidation = $this->codeValidation();
        if ($codeValidation->getStatusCode() !== 200) {
            return $codeValidation;
        }
        if ($request->status == true) {
            $user = DB::table('users');
            $user->where('verification_code', $this->request->code);
            $user->update([
                'password' => Hash::make($this->request->password),
                'verification_code' => null,
                'expire_at' => null,
            ]);
            return $this->response(200, 'Password baru berhasil disimpan.');
        } else {
            $user = DB::table('password_reset_tokens');
            $user->where('verification_code', $this->request->code);
            $user->select('email');
            $user = $user->first();

            try {
                // Update password user
                $user = DB::table('users');
                $user->where('email', $user->email);
                $user->update([
                    'password' => Hash::make($this->request->password),
                ]);

                $user = DB::table('password_reset_tokens');
                $user->where('verification_code', $this->request->code);
                $user->delete();

                return $this->response(200, 'Reset password berhasil disimpan.');
            } catch (\Throwable $th) {
                return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
            }
        }
    }

    /**
     * Logout
     *
     * Below is the endpoint designated for logging out, allowing users to securely terminate their session and prevent unauthorized access to their account.
     * @authenticated
     * @response 200{"code": 200,"message": "Pengguna berhasil logout.","data": null}
     * @response 401 {"code": 401,"message": "Anda harus login terlebih dahulu!","data": null}
     */
    public function logout()
    {
        $user = $this->request->user();
        $user->tokens()->where('id', auth()->id())->delete();
        return $this->response(200, 'Pengguna berhasil logout.');
    }

    /**
     * Valdation for code token
     *
     * @return void
     */
    public function codeValidation()
    {
        if ($this->request->status == true) {
            $user = DB::table("users");
        } else {
            $user = DB::table("password_reset_tokens");
        }
        $user->where('verification_code', $this->request->code);
        $user->select('verification_code', 'expire_at');
        $user = $user->first();

        if (!$user) {
            return $this->response(404, 'Verifikasi kode tidak tersedia.');
        } else if ($user->expire_at < date('Y-m-d')) {
            return $this->response(404, 'Verifikasi kode sudah kadaluarsa.');
        } else {
            return $this->response(200, 'Verifikasi kode berhasil.');
        }
    }
}
