<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return $this->response(422, 'Alamat email atau kata sandi yang digunakan salah.');
        }

        $user = Auth::user();
        $token = $user->createToken('bearer')->plainTextToken;

        return response()->json([
            'code' => 200,
            "message" => "Pengguna berhasil login.",
            "token" => $token,
        ], 200);
    }

    public function logout()
    {
        $user = $this->request->user();
        $user->currentAccessToken()->delete();
        return $this->response(200, 'Pengguna berhasil logout.');
    }
}
