<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GradeRepository
{
    public function getDetail($userId)
    {
        $userGrades = DB::table('user_grades');
        $userGrades->where('user_id', $userId);
        $userGrades->select(
            'period_month',
            'period_year',
            'grade_id',
            'effective_date',
            'decree_name',
            'decree_document',
            'type_of_decree',
            'decree_number',
            'decree_date',
            'description',
            'status'
        );
        return $userGrades = $userGrades->get();
    }
}
