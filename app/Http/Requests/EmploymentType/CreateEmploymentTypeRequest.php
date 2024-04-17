<?php

namespace App\Http\Requests\EmploymentType;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmploymentTypeRequest extends FormRequest
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
            'name' => 'required|max:160',
            'status' => 'required|boolean',
            'type' => 'required|in:1,2,3',
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
            'name.max' => 'Nama tidak boleh lebih dari 160 karakter.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.boolean' => 'Status harus berupa boolean.',
            'type.required' => 'Tipe tidak boleh kosong.',
            'type.in' => 'Tipe harus diantara 1, 2 atau 3.',
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
                'description' => 'Refers to the Name of Employment Type.',
                'example' => 'Staf Khusus',
            ],
            'status' => [
                'description' => 'Refers to the Status of Employment Type.',
                'example' => true,
            ],
            'type' => [
                'description' => 'Refers to the Type of Employment Type. 1=ASN, 2=NON-ASN or 3=OUTSOURCE',
                'example' => 1,
            ],
        ];
    }
}
