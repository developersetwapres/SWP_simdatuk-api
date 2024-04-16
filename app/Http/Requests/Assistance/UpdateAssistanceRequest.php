<?php

namespace App\Http\Requests\Assistance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssistanceRequest extends FormRequest
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
            'name' => 'required|unique:assistances,name,' . $this->id,
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
            'name.required' => 'Nama tidak boleh kosong.',
            'name.unique' => 'Nama sudah digunakan.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.boolean' => 'Status harus berupa boolean',
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
            'name' => [
                'description' => 'Refers to the Name of Assistance.',
                'example' => 'Staf Khusus',
            ],
            'status' => [
                'description' => 'Refers to the Status of Assistance.',
                'example' => true,
            ],
        ];
    }
}
