<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DisciplineRepository
{
    public function getDetail($userId)
    {
        $userDisciplinaries = DB::table('user_disciplinaries');
        $userDisciplinaries->where('user_id', $userId);
        $userDisciplinaries->select(
            'period_month',
            'period_year',
            'grade_id',
            'position',
            'penalty',
            'decree_number',
            'date_of_decree',
            'start_date',
            'end_date',
            'status',
            'description',
            'authorizing_officer',
            'name_of_authorizing_officer',
            'level',
            'type',
        );
        return $userDisciplinaries = $userDisciplinaries->get();
    }
}
