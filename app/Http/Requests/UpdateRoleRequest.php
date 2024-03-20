<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_name' => ['string', 'min:3'],
            'permission' => ['array'],
            'permission.actions' => ['array'],
            'permission.actions.read' => ['boolean'],
            'permission.actions.create' => ['boolean'],
            'permission.actions.update' => ['boolean'],
            'permission.actions.delete' => ['boolean'],
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
