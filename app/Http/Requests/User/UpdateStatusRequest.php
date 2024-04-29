<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
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
            'id' => 'required|numeric',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Return custom error response
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID tidak boleh kosong.',
            'id.numeric' => 'ID harus berupa angka.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.boolean' => 'ID harus berupa boolean.',
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
            'id' => [
                'description' => 'Refers to the ID of User.',
                'example' => 1,
            ],
            'status' => [
                'description' => 'Field to update status user active or deactivate.',
                'example' => 1,
            ],
        ];
    }
}
