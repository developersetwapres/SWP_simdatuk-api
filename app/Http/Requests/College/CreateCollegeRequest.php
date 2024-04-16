<?php

namespace App\Http\Requests\College;

use Illuminate\Foundation\Http\FormRequest;

class CreateCollegeRequest extends FormRequest
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
            'name' => 'required|unique:colleges,name',
            'region' => 'required|boolean',
            'address' => 'max:160',
            'accreditation' => 'max:2',
            'accreditation_certificate' => 'image|max:512',
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
            'region.boolean' => 'Region harus berupa boolean',
            'adderss.max' => 'Alamat tidak boleh lebih dari 160 karakter',
            'accreditation.max' => 'Akreditasi tidak boleh lebih dari 2 karakter',
            'accreditation_certificate.image' => 'Sertifikat harus berupa jpg, jpeg, png, bmp, gif, svg, atau webp.',
            'accreditation_certificate.max' => 'Ukuran Sertifikat tidak boleh lebih dari 512kb.',
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
                'example' => 'Universitas Indonesia',
            ],
            'region' => [
                'description' => 'Refers to the Region of College.',
                'example' => false,
            ],
            'address' => [
                'description' => 'Refers to the Address of College.',
                'example' => 'Jakarta',
            ],
            'accreditation' => [
                'description' => 'Refers to the Accreditation of College. A | B | C | D',
                'example' => 'A',
            ],
            'accreditation_certificate' => [
                'description' => 'Refers to the Accreditation Certificate of College.',
                'example' => public_path('/img/logo.svg'),
            ],
        ];
    }
}
