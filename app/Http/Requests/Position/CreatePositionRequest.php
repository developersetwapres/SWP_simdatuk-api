<?php

namespace App\Http\Requests\Position;

use App\Http\Requests\PositionEchelon\CreatePositionEchelonRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePositionRequest extends FormRequest
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
        $userRules = [
            'name' => 'required|max:512',
            'parent_id' => 'numeric|nullable',
            'available' => 'numeric',
            'type' => 'required|in:1,2,3',
            'entity' => 'required|in:1,2',
            'order' => 'required|numeric',
        ];
        return array_merge(
            $userRules,
            CreatePositionEchelonRequest::rules(),
        );
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        $userMessages = [
            'name.required' => 'Nama Jabatan tidak boleh kosong.',
            'name.max' => 'Nama Jabatan tidak boleh lebih dari 512 karakter.',
            'parent_id.numeric' => 'Parent ID harus berupa angka.',
            'available.numeric' => 'Available harus berupa angka.',
            'type.required' => 'Type tidak boleh kosong.',
            'type.in' => 'Type harus diantara 1, 2, atau 3.',
            'entity.required' => 'Entity tidak boleh kosong.',
            'entity.in' => 'Entity harus diantara 1 atau 2.',
            'order.required' => 'Order tidak boleh kosong.',
            'order.numeric' => 'Order harus berupa angka.',
        ];

        return array_merge(
            $userMessages,
            CreatePositionEchelonRequest::messages(),
        );
    }

    /**
     * Description for scribe
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        $userBodyParameters = [
            'name' => [
                'description' => 'Refers to the Name of Position.',
                'example' => 'Kepala Sekretariat Wakil Presiden',
            ],
            'parent_id' => [
                'description' => 'Refers to the id parent of Position.',
                'example' => 1,
            ],
            'available' => [
                'description' => 'Refers to the Available count for fill of Position.',
                'example' => 1,
            ],
            'type' => [
                'description' => 'Refer to Position Type. 1=Structural, 2=Functional, 3=Operational',
                'example' => 1,
            ],
            'entity' => [
                'description' => 'Refers to the Entity Type of Position. 1=Individu, 2=Group (card)',
                'example' => 1,
            ],
            'order' => [
                'description' => 'Refers to the Order placing position on graph.',
                'example' => 1,
            ],
        ];

        return array_merge(
            $userBodyParameters,
            CreatePositionEchelonRequest::bodyParameters(),
        );
    }
}
