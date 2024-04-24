<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class EmployeeRepository
{
    public function getDetail($userId)
    {
        $user = DB::table('users');
        $user->where('id', $userId);
        $user->select(
            'id',
            'email',
            'name',
            'photo_profile',
            'id_number',
            'employee_id_number',
            'employee_registration_number',
            'place_of_birth',
            'date_of_birth',
            'religion',
            'gender',
            'marital_status',
            'employment_type_id',
            'grade_id',
            'grade_effective_date',
            'position_id',
            'echelon_id',
            'echelon_effective_date',
            'institution_id',
            'organization_id',
            'work_unit_id',
            'employee_id_card_number',
            'employee_id_card',
            'wife_id_card_number',
            'husband_id_card_number',
            'id_tax',
            'employment_status',
            'inner_housing_complex',
            'current_address',
            'home_phone_number',
            'mobile_phone',
            'office_address',
            'office_phone_number',
            'description',
            'type',
            'created_at'
        );
        return $user = $user->first();
    }
}
