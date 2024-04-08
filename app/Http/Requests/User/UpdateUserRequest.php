<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'role_id' => 'required|numeric',
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
            'role_id.required' => 'Role id tidak boleh kosong.',
            'role_id.numeric' => 'Role id harus berupa angka.',
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
            'role_id' => [
                'description' => 'Refers to the ID of Role.',
                'example' => 1,
            ],
        ];
    }
}
