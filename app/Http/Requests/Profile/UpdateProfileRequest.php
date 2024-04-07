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
            'foto_profil' => 'image|max:512',
            'username' => 'required|min:5|max:30|unique:users,username,' . $this->user()?->id,
            'email' => 'required|email|unique:users,email,' . $this->user()?->id,
            'old_password' => 'required_with:password',
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable|required_with:password|min:6',
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
            'foto_profil.image' => 'Foto profil harus berupa jpg, jpeg, png, bmp, gif, svg, atau webp.',
            'foto_profil.max' => 'Ukuran foto profil tidak boleh lebih dari 512kb.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.min' => 'Username tidak boleh kurang dari 5 karakter',
            'username.max' => 'Username tidak boleh lebih dari 30 karakter',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak sesuai.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Minimal password baru harus 6 karakter atau lebih.',
            'password.confirmed' => 'Konfirmasi password baru harus sama.',
            'password_confirmation.required_with' => 'Konfirmasi password baru tidak boleh kosong.',
            'password_confirmation.min' => 'Minimal konfirmasi password baru harus 6 karakter atau lebih.',
            'old_password.required_with' => 'Password saat ini tidak boleh kosong.',
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
            'foto_profil' => [
                'description' => 'Refers to the Photo Profile of User.',
                'example' => public_path('/img/logo.svg'),
            ],
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
                'example' => 'current_password',
            ],
            'password' => [
                'description' => 'Refers to the New Password of User.',
                'example' => 'new_password',
            ],
            'password_confirmation' => [
                'description' => 'Refers to the Password Confirmation of User.',
                'example' => 'new_password',
            ],
        ];
    }
}
