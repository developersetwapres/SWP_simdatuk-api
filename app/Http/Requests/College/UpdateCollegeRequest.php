<?php

namespace App\Http\Requests\College;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollegeRequest extends FormRequest
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
            'name' => 'required|unique:colleges,name,' . $this->id,
            'region' => 'required|boolean',
            'address' => 'max:160',
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
            'region.required' => 'Region tidak boleh kosong.',
            'region.required' => 'Region harus berupa boolean',
            'adderss.max' => 'Alamat tidak boleh lebih dari 160 karakter',
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
                'description' => 'Refers to the Name of College.',
                'example' => 'admin',
            ],
            'region' => [
                'description' => 'Refers to the Region of College.',
                'example' => 'false',
            ],
            'address' => [
                'description' => 'Refers to the Address of College.',
                'example' => 'Jakarta',
            ],
        ];
    }
}
