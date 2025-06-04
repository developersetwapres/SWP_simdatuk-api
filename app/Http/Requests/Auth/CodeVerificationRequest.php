<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CodeVerificationRequest extends FormRequest
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
                'example' => true,
            ],
        ];
    }
}
