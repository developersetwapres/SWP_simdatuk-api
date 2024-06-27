<?php

namespace App\Http\Requests\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExportZipEmployeesRequest extends FormRequest
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
            'organization' =>    'array|min:1',
            'employee_type' => 'array|min:1',
            'echelons' => 'array|min:1',
            'grades' => 'array|min:1',
            'job_description' => 'array|min:1',
            'education' => 'array|min:1',
            'gender' => 'array|min:1|max:2',
            'min_age' => 'numeric|min:1',
            'max_age' => 'numeric',
            'marital_status' => 'array|min:1',
            'grade_range' => 'array|min:1',
            'total_working_duration' => 'array|min:1',
            'deputy' => 'array|min:1'
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
            'organization.array' => 'harus dalam rupa array',
            'organization.min' => 'minimal 1 angka didalam array',
            'organization.numeric' => 'isi array harus berupa angka',
            'employee_type.array' => 'harus dalam rupa array',
            'employee_type.min' => 'minimal 1 angka didalam array',
            'employee_type.numeric' => 'isi array harus berupa angka',
            'echelons.array' => 'harus dalam rupa array',
            'echelons.min' => 'minimal 1 angka didalam array',
            'echelons.numeric' => 'isi array harus berupa angka',
            'grades.array' => 'harus dalam rupa array',
            'grades.min' => 'minimal 1 angka didalam array',
            'grades.numeric' => 'isi array harus berupa angka',
            'job_description.array' => 'harus dalam rupa array',
            'job_description.min' => 'minimal 1 angka didalam array',
            'education.array' => 'harus dalam rupa array',
            'education.min' => 'minimal 1 angka didalam array',
            'education.numeric' => 'isi array harus berupa angka',
            'gender.array' => 'harus dalam rupa array',
            'gender.min' => 'minimal 1 angka didalam array',
            'gender.max' => 'maksimal hanya 2 angka didalam array',
            'gender.numeric' => 'isi array harus berupa angka',
            'marital_status.array' => 'harus dalam rupa array',
            'marital_status.min' => 'minimal 1 angka didalam array',
            'marital_status.numeric' => 'isi array harus berupa angka',
            'min_age.min' => 'array minimal harus ada 1 value',
            'min_age.numeric' => 'harus dalam rupa angka',
            'max_age.numeric' => 'harus dalam rupa angka',
            'grade_range.array' => 'Age range harus berupa array',
            'grade_range.min' => 'Age range harus memiliki minimal 1 item',
            'total_working_duration.array' => 'Age range harus berupa array',
            'total_working_duration.min' => 'Age range harus memiliki minimal 1 item',
            'deputy.array' => 'Age range harus berupa array',
            'deputy.min' => 'Age range harus memiliki minimal 1 item',
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
            'organization' => [
                'description' => 'Refers to IDs of Organization',
                'example' => [1]
            ],
            'employee_type' => [
                'description' => 'Refers to IDs of type of employee (1: ASN, 2: Non ASN, 3: Outsourcing)',
                'example' => [1]
            ],
            'echelons' => [
                'description' => 'Refers to IDs of employee echelons',
                'example' => [1]
            ],
            'grades' => [
                'description' => 'Refers to IDs of employee grades',
                'example' => [1]
            ],
            'job_description' => [
                'description' => 'Refers to IDs of employee position status',
                'example' => [1]
            ],
            'education' => [
                'description' => 'Refers to type of employee education (1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat, 4=Akademik/D3/S.Muda, 5=Diploma IV, 6=Strata I, 7=Strata II, 8=Strata III)',
                'example' => [1]
            ],
            'gender' => [
                'description' => 'Refers to gender of employee (1: Laki - Laki, 0: Perempuan)',
                'example' => [1]
            ],
            'marital_status' => [
                'description' => 'Refers to marital status of employee (1=Belum Menikah, 2=Menikah, 3=Cerai Hidup, 4=Cerai Mati)',
                'example' => [1]
            ],
            'min_age' => [
                'description' => 'Refers to minimum age of employee',
                'example' => 50
            ],
            'max_age' => [
                'description' => 'Refers to maximum age of employee',
                'example' => 55
            ],
            'grade_range' => [
                'description' => 'Refers to duration of grade in years',
                'example' => ["5-10"],
            ],
            'total_working_duration' => [
                'description' => 'Refers to total duration of employee employment',
                'example' => ["5-10"],
            ],
            'deputy' => [
                'description' => 'Refers to Deputy ids list of employees',
                'example' => [5],
            ]
        ];
    }
}
