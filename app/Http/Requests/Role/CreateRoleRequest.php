<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
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
            'name' => 'required|unique:roles,name',
            'permissions.*.id' => 'required|numeric',
            'permissions.*.permitted_actions' => 'required|string',
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
            'permissions.*.id.required' => 'Permission id tidak boleh kosong.',
            'permissions.*.id.numeric' => 'Permission id harus berupa angka.',
            'permissions.*.permitted_actions.required' => 'Permission permitted actions tidak boleh kosong.',
            'permissions.*.permitted_actions.string' => 'Permission permitted actions harus berupa string.',
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
                'description' => 'Refers to the Name of Role.',
                'example' => 'admin',
            ],
            'permissions.*.id' => [
                'description' => 'Refers to the ID of Role Permission.',
                'example' => 1,
            ],
            'permissions.*.permitted_actions' => [
                'description' => 'Refers to the Permitted Actions of Role Permission.',
                'example' => 'crud',
            ],
        ];
    }
}
