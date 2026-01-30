<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpVerifyRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Models\UserDeviceSession;
use App\Traits\SingleDeviceLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * @group Authentication
 * Below is a list of endpoints crucial for authentication processes, including login, forgot password, token verification, reset password, and logout, facilitating secure and authenticated access for users.
 */
class AuthController extends Controller
{
    use SingleDeviceLogin;
    
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
            return $this->response(401, 'Terjadi kesalahan, silakan coba lagi.');
        } else if (is_null($user->role_id)) {
            return $this->response(401, 'Terjadi kesalahan, silakan coba lagi.');
        } else if ($user->status != true) {
            return $this->response(401, 'Terjadi kesalahan, silakan coba lagi.');
        }

        // Validate reCAPTCHA in production
        if (config('app.env') == 'production') {
            $recaptchaValidation = $this->recaptchaValidation($this->request->recaptcha_token);
            if ($recaptchaValidation->getStatusCode() !== 200) {
                return $recaptchaValidation;
            }
        }

        // Handle single device login - revoke other sessions and create new token
        $tokenResult = $this->handleSingleDeviceLogin($user, $this->request);
        $token = $tokenResult->plainTextToken;

        // Get user data with role and permissions
        $userData = $this->getUserData($user->id);

        return response()->json([
            'code' => 200,
            "message" => "Pengguna berhasil login.",
            "token" => $token,
            "user" => $userData,
            "device_info" => [
                "device_type" => $this->getDeviceName($this->request),
                "login_time" => now()->toISOString(),
                "previous_sessions_revoked" => true
            ]
        ], 200);
    }

    /**
     * Get formatted user data with role and permissions
     */
    private function getUserData($userId)
    {
        $user = DB::table('users');
        $user->select('users.id', 'users.email', 'users.username', 'users.photo_profile', 'users.employee_id_number', 'users.employee_registration_number');
        $user->where('users.id', $userId);
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

        return $user;
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
            return $this->response(404, 'Terjadi kesalahan, silakan coba lagi.');
        }

        // Generete Token
        $token = new User();
        $this->request->verification_code = $token->generateOtp();

        DB::table('otps')->insert([
            'email' => $this->request->email,
            'code' => $this->request->verification_code,
            'expire_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Send Email
        try {
            Mail::to($this->request->email)->send(new ForgotPassword($this->request));
        } catch (\Exception $e) {
            return $this->response(404, 'Gagal mengirimkan email, silakan hubungi admin.');
        }

        return $this->response(200, 'Email sudah dikirim.');
    }

    /**
     * Reset Password
     *
     * Below are the endpoints designed for resetting password.
     * @response 200 {"code": 200,"message": "Reset password berhasil disimpan","data": null}
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = DB::table('password_reset_tokens');
        $user->where('verification_code', $this->request->reset_token);
        $user->select('email', 'expire_at');
        $user = $user->first();

        if ($user) {
            if ($user->expire_at >= date('Y-m-d H:i:s')) {
                $query = DB::table('users');
                $query->where('email', $user->email);
                $query->update([
                    'password' => Hash::make($this->request->password),
                ]);

                DB::table('password_reset_tokens')->where('verification_code', $this->request->reset_token)->delete();
                return $this->response(200, 'Reset password berhasil disimpan.');
            } else {
                DB::table('password_reset_tokens')->where('expire_at', '<', date('Y-m-d H:i:s'))->delete();
                return $this->response(404, 'Reset token sudah kadaluarsa.');
            }
        } else {
            return $this->response(404, 'Terjadi kesalahan, silakan coba lagi.');
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
        $currentToken = $user->currentAccessToken();
        
        // Remove device session record
        UserDeviceSession::where('sanctum_token_id', $currentToken->id)->delete();
        
        // Delete the current token
        $currentToken->delete();
        
        return $this->response(200, 'Pengguna berhasil logout.');
    }

    /**
     * Logout from all devices
     *
     * Force logout from all devices for current user
     * @authenticated  
     */
    public function logoutAllDevices()
    {
        $user = $this->request->user();
        
        // Revoke all tokens and device sessions
        $user->revokeOtherDeviceSessions();
        
        return $this->response(200, 'Berhasil logout dari semua perangkat.');
    }

    /**
     * Get active device sessions
     *
     * Get list of active device sessions for current user
     * @authenticated
     */
    public function getActiveSessions()
    {
        $user = $this->request->user();
        
        $sessions = UserDeviceSession::where('user_id', $user->id)
            ->orderBy('last_activity_at', 'desc')
            ->get(['device_name', 'ip_address', 'last_activity_at', 'created_at']);
        
        return response()->json([
            'code' => 200,
            'message' => 'Berhasil mengambil data sesi aktif.',
            'data' => $sessions
        ], 200);
    }

    /**
     * Verify OTP
     *
     * Below are the endpoints designed for setting new password.
     * @response 200 {"code": 200,"message": "Kode OTP berhasil diverifikasi.","reset_token": "HJ7xKpi0z4wpSas306CTuRNjULb7dNve8qPDMTxK65ded5a7"}
     */
    public function verifyOtp(OtpVerifyRequest $request)
    {
        $otp = DB::table('otps');
        $otp->where('email', $this->request->email);
        $otp->where('code', $this->request->otp);
        $otp->select('email', 'expire_at');
        $otp = $otp->first();
        
        if ($otp) {
            if ($otp->expire_at >= date('Y-m-d H:i:s')) {
                $token = new User();
                $this->request->token = $token->generateToken(false);
                
                DB::table('password_reset_tokens')->insert([
                    'email' => $otp->email,
                    'verification_code' => $this->request->token,
                    'expire_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                DB::table('otps')->where('code', $this->request->otp)->delete();

                return response()->json([
                    'code' => 200,
                    "message" => "Kode OTP berhasil diverifikasi.",
                    "reset_token" => $this->request->token,
                ], 200);
            } else {
                DB::table('otps')->where('expire_at', '<', date('Y-m-d H:i:s'))->delete();
                return $this->response(404, 'Kode OTP sudah kadaluarsa.');
            }
        } else {
            return $this->response(404, 'Kode OTP tidak ditemukan.');
        }
    }

    private function recaptchaValidation($token)
    {
        $secretKey = env('RECAPTCHA_SECRET_KEY'); // Store your secret key in .env

        // Make a request to Google's API to verify the reCAPTCHA response
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
        ]);

        $result = $response->json();

        if (!$result['success']) {
            return $this->response(404, 'reCAPTCHA verification failed.');
        } else {
            return $this->response(200, 'success');
        }
    }
}
