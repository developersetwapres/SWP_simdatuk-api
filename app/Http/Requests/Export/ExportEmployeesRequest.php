<?php

namespace App\Http\Requests\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExportEmployeesRequest extends FormRequest
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
            'organization.*' => 'array|min:1|numeric',
            'employee_type.*' => 'array|min:1|numeric',
            'echelons.*' => 'array|min:1|numeric',
            'grades.*' => 'array|min:1|numeric',
            'position_status.*' => 'array|min:1|numeric',
            'education.*' => 'array|min:1|numeric',
            'gender.*' => 'array|min:1|max:2|numeric',
            'age_range.*' => 'array|min:1|string',
            'marital_status.*' => 'array|min:1|numeric',
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
            'organization.*.array' => 'harus dalam rupa array',
            'organization.*.min' => 'minimal 1 angka didalam array',
            'organization.*.numeric' => 'isi array harus berupa angka',
            'employee_type.*.array' => 'harus dalam rupa array',
            'employee_type.*.min' => 'minimal 1 angka didalam array',
            'employee_type.*.numeric' => 'isi array harus berupa angka',
            'echelons.*.array' => 'harus dalam rupa array',
            'echelons.*.min' => 'minimal 1 angka didalam array',
            'echelons.*.numeric' => 'isi array harus berupa angka',
            'grades.*.array' => 'harus dalam rupa array',
            'grades.*.min' => 'minimal 1 angka didalam array',
            'grades.*.numeric' => 'isi array harus berupa angka',
            'position_status.*.array' => 'harus dalam rupa array',
            'position_status.*.min' => 'minimal 1 angka didalam array',
            'position_status.*.numeric' => 'isi array harus berupa angka',
            'education.*.array' => 'harus dalam rupa array',
            'education.*.min' => 'minimal 1 angka didalam array',
            'education.*.numeric' => 'isi array harus berupa angka',
            'gender.*.array' => 'harus dalam rupa array',
            'gender.*.min' => 'minimal 1 angka didalam array',
            'gender.*.max' => 'maksimal hanya 2 angka didalam array',
            'gender.*.numeric' => 'isi array harus berupa angka',
            'marital_status.*.array' => 'harus dalam rupa array',
            'marital_status.*.min' => 'minimal 1 angka didalam array',
            'marital_status.*.numeric' => 'isi array harus berupa angka',
            'age_range.*.array' => 'harus dalam rupa array',
            'age_range.*.min' => 'array minimal harus ada 1 value',
            'age_range.*.string' => 'harus dalam rupa range umur',
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
            'organization.*' => [
                'description' => 'Refers to IDs of Organization',
            ],
            'employee_type.*' => [
                'description' => 'Refers to IDs of type of employee (1: ASN, 2: Non ASN, 3: Outsourcing)',
            ],
            'echelons.*' => [
                'description' => 'Refers to IDs of employee echelons',
            ],
            'grades.*' => [
                'description' => 'Refers to IDs of employee grades',
            ],
            'position_status.*' => [
                'description' => 'Refers to IDs of employee position status',
            ],
            'education.*' => [
                'description' => 'Refers to type of employee education (1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat,
                 4=Akademik/D3/S.Muda, 5=Diploma IV, 6=Strata I, 7=Strata II, 8=Strata III )',
            ],
            'gender.*' => [
                'description' => 'Refers to gender of employee (1 : Laki - Laki, 0 : Perempuan)',
            ],
            'marital_status.*' => [
                'description' => 'Refers to marital status of employee (1=Belum Menikah, 2=Menikah, 3=Cerai Hidup, 4=Cerai Mati)',
            ],
            'age_range.*' => [
                'description' => 'Refers to age range of employee',
            ],
        ];
    }
}
