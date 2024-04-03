<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'code' => 'required',
            'status' => 'required|boolean',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
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
            'code.required' => 'Kode tidak boleh kosong.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.boolean' => 'Status harus berupa boolean.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal memiliki 6 karakter.',
            'password.confirmed' => 'Konfirmasi password harus sama.',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
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
            'code' => [
                'description' => 'Verification code from email.',
                'example' => 'HJ7xKpi0z4wpSas306CTuRNjULb7dNve8qPDMTxK65ded5a7',
            ],
            'status' => [
                'description' => 'Status of code, true for register and false for forgot password.',
                'example' => 'true',
            ],
            'password' => [
                'description' => 'Refers to the Password of User.',
                'example' => '**********',
            ],
            'password_confirmation' => [
                'description' => 'Refers to the Password Confirmation of User.',
                'example' => '**********',
            ],
        ];
    }
}
