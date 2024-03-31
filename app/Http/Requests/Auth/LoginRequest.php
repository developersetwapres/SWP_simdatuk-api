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
        return [
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ];
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
            'username.exists' => 'Username anda tidak terdaftar.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
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
        ];
    }
}
