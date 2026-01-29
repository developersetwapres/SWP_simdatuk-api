<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (config('app.env') == 'production') {
            return [
                'username' => 'required|exists:users,username',
                'password' => 'required',
                'recaptcha_token' => 'required',
            ];
        } else {
            return [
                'username' => 'required|exists:users,username',
                'password' => 'required',
            ];
        }
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username tidak boleh kosong.',
            'username.exists' => 'Terjadi kesalahan, silakan coba lagi.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
            'recaptcha_token.required' => 'Token recaptcha tidak boleh kosong.',
        ];
    }

    /**
     * Description for scribe
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'username' => [
                'description' => 'Username of user.',
                'example' => 'admin',
            ],
            'password' => [
                'description' => 'Password of user.',
                'example' => 'password',
            ],
            'recaptcha_token' => [
                'description' => 'Recaptcha token.',
                'example' => '03AFcWeA4z8yaB38pOzK',
            ],
        ];
    }
}
