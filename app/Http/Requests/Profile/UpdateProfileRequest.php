<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'username' => 'required|min:6|max:30|unique:users,username,' . $this->id,
            'email' => 'required|email|unique:users,email,' . $this->id,
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'foto_profil' => 'max: ',
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
            'username.min' => 'Username tidak boleh kurang dari 6 karakter',
            'username.max' => 'Username tidak boleh lebih dari 30 karakter',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak sesuai.',
            'email.unique' => 'Email sudah digunakan.',
            'old_password.required' => 'Password saat ini tidak boleh kosong.',
            'password.required' => 'Password baru tidak boleh kosong.',
            'password.min' => 'Minimal password baru harus 6 karakter atau lebih.',
            'password.confirmed' => 'Konfirmasi password baru harus sama.',
            'password_confirmation.required' => 'Konfirmasi password baru tidak boleh kosong.',
            'password_confirmation.min' => 'Minimal konfirmasi password baru harus 6 karakter atau lebih.',
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
                'description' => 'Refers to the Username of User.',
                'example' => 'admin',
            ],
            'email' => [
                'description' => 'Refers to the Email of User.',
                'example' => 'admin@simdatuk.go.id',
            ],
            'old_password' => [
                'description' => 'Refers to the Current Password of User.',
                'example' => '******',
            ],
            'password' => [
                'description' => 'Refers to the New Password of User.',
                'example' => '******',
            ],
            'password_confirmation' => [
                'description' => 'Refers to the Password Confirmation of User.',
                'example' => '******',
            ],
            'foto_profil' => [
                'description' => 'Refers to the Photo Profile of User.',
                'example' => '******',
            ],
        ];
    }
}
