<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'min:6', 'max:16'],
            'password' => ['required', 'min:8', 'max:16'],
            'email' => ['required', 'email'],
            'pegawai_id' => ['required', 'numeric'],
            'role_id' => ['required','numeric'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 400,
            'status' => 'bad request',
            'errors' => $validator->errors(),
            'data' => null
        ], 400));
    }
}
