<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * APIs for authentication
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
     * @bodyParam email string required email for authentication. Example: example@domain.com
     * @bodyParam password string required password for authentication. Example: password
     * @response 200 {"code": 200,"message": "Pengguna berhasil login.","token": "10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e"}
     * @response 422 {"code": 422,"message": "Alamat email tidak boleh kosong.","data": {"email": ["Alamat email tidak boleh kosong."], "password": ["Kata sandi tidak boleh kosong."]}}
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->response(422, 'Alamat email atau kata sandi yang digunakan salah.');
        }
        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'code' => 200,
            "message" => "Pengguna berhasil login.",
            "token" => $token,
        ], 200);
    }

    /**
     * Logout
     * @authenticated
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     */
    public function logout()
    {
        $user = $this->request->user();
        $user->tokens()->where('id', auth()->id())->delete();
        return $this->response(200, 'Pengguna berhasil logout.');
    }
}
