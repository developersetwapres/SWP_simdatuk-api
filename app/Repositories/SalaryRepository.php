<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SalaryRepository
{
    public function getDetail($userId)
    {
        $userSalaries = DB::table('user_salaries');
        $userSalaries->where('user_id', $userId);
        $userSalaries->select(
            'period_month',
            'period_year',
            'grade_id',
            'effective_date',
            'decree_number',
            'length_of_service_month',
            'length_of_service_year',
            'previous_basic_salary',
            'new_basic_salary',
            'description'
        );
        return $userSalaries = $userSalaries->get();
    }
}
