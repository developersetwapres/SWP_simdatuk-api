<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'email' => 'email|unique:users,email',
            'name' => 'required|max:160',
            'photo_profile' => 'image|max:512',
            'id_number' => 'numeric|digits:16|unique:users,id_number',
            'employee_id_number' => 'numeric|min:5|max:10|unique:users,employee_id_number',
            'employee_registration_number' => 'numeric|min:5|max:10|unique:users,employee_registration_number',
            'place_of_birth' => 'required|max:160',
            'date_of_birth' => 'required|date',
            'religion' => 'required|in:1,2,3,4,5,6',
            'gender' => 'required|boolean',
            'marital_status' => 'required|in:1,2,3,4,5',
            'employment_type_id' => 'required',
            'grade_id' => 'numeric',
            'grade_effective_date' => 'date',
            'position_id' => 'numeric',
            'echelon_id' => 'numeric',
            'echelon_effective_date' => 'date',
            'institution_id' => 'numeric',
            'organization_id' => 'numeric',
            'work_unit_id' => 'numeric',
            'employee_id_card_number' => 'numeric|min:5|max:10|unique:users,employee_id_card_number',
            'employee_id_card' => 'image|max:512',
            'wife_id_card_number' => 'numeric|min:5|max:10|unique:users,wife_id_card_number',
            'husband_id_card_number' => 'numeric|min:5|max:10|unique:users,husband_id_card_number',
            'id_tax' => 'numeric|digits:16|unique:users,id_tax',
            'employment_status' => 'boolean',
            'inner_housing_complex' => 'boolean',
            'housing_complex_name' => 'max:160',
            'current_address' => 'max:160',
            'home_phone_number' => 'numeric|max:14',
            'mobile_phone' => 'numeric|max:14',
            'office_address' => 'max:160',
            'office_phone_number' => 'numeric|max:14',
            'description' => 'max:160',
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
            'type.required' => 'Tipe tidak boleh kosong.',
            'type.numeric' => 'Tipe harus berupa angka.',
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
            'email' => [
                'description' => 'Refers to the Email of Employee.',
                'example' => 'admin@simdatuk.go.id',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee.',
                'example' => 'Admin',
            ],
            'photo_profile' => [
                'description' => 'Refers to the Photo Profile of Employee',
                'example' => public_path('/img/logo.svg'),
            ],
            'id_number' => [
                'description' => 'Refers to the ID Number of Employee',
                'example' => '',
            ],
            'employee_id_number' => [
                'description' => 'Refers to the Employee ID Number of Employee',
                'example' => '',
            ],
            'employee_registration_number' => [
                'description' => 'Refers to the employee_registration_number of Employee',
                'example' => '',
            ],
            'place_of_birth' => [
                'description' => 'Refers to the place_of_birth of Employee',
                'example' => '',
            ],
            'date_of_birth' => [
                'description' => 'Refers to the date_of_birth of Employee',
                'example' => '',
            ],
            'religion' => [
                'description' => 'Refers to the religion of Employee',
                'example' => '',
            ],
            'gender' => [
                'description' => 'Refers to the gender of Employee',
                'example' => '',
            ],
            'marital_status' => [
                'description' => 'Refers to the marital_status of Employee',
                'example' => '',
            ],
            'employment_type_id' => [
                'description' => 'Refers to the employment_type_id of Employee',
                'example' => '',
            ],
            'grade_id' => [
                'description' => 'Refers to the grade_id of Employee',
                'example' => '',
            ],
            'grade_effective_date' => [
                'description' => 'Refers to the grade_effective_date of Employee',
                'example' => '',
            ],
            'position_id' => [
                'description' => 'Refers to the position_id of Employee',
                'example' => '',
            ],
            'echelon_id' => [
                'description' => 'Refers to the echelon_id of Employee',
                'example' => '',
            ],
            'echelon_effective_date' => [
                'description' => 'Refers to the echelon_effective_date of Employee',
                'example' => '',
            ],
            'institution_id' => [
                'description' => 'Refers to the institution_id of Employee',
                'example' => '',
            ],
            'organization_id' => [
                'description' => 'Refers to the organization_id of Employee',
                'example' => '',
            ],
            'work_unit_id' => [
                'description' => 'Refers to the work_unit_id of Employee',
                'example' => '',
            ],
            'employee_id_card_number' => [
                'description' => 'Refers to the employee_id_card_number of Employee',
                'example' => '',
            ],
            'employee_id_card' => [
                'description' => 'Refers to the employee_id_card of Employee',
                'example' => public_path('/img/logo.svg'),
            ],
            'wife_id_card_number' => [
                'description' => 'Refers to the wife_id_card_number of Employee',
                'example' => '',
            ],
            'husband_id_card_number' => [
                'description' => 'Refers to the husband_id_card_number of Employee',
                'example' => '',
            ],
            'id_tax' => [
                'description' => 'Refers to the id_tax of Employee',
                'example' => '',
            ],
            'employment_status' => [
                'description' => 'Refers to the employment_status of Employee',
                'example' => '',
            ],
            'inner_housing_complex' => [
                'description' => 'Refers to the inner_housing_complex of Employee',
                'example' => '',
            ],
            'housing_complex_name' => [
                'description' => 'Refers to the housing_complex_name of Employee',
                'example' => '',
            ],
            'current_address' => [
                'description' => 'Refers to the current_address of Employee',
                'example' => '',
            ],
            'home_phone_number' => [
                'description' => 'Refers to the home_phone_number of Employee',
                'example' => '',
            ],
            'mobile_phone' => [
                'description' => 'Refers to the mobile_phone of Employee',
                'example' => '',
            ],
            'office_address' => [
                'description' => 'Refers to the office_address of Employee',
                'example' => '',
            ],
            'office_phone_number' => [
                'description' => 'Refers to the office_phone_number of Employee',
                'example' => '',
            ],
            'description' => [
                'description' => 'Refers to the description of Employee',
                'example' => '',
            ],
            'type' => [
                'description' => 'Refers to the type of Employee',
                'example' => '',
            ],
        ];
    }
}
