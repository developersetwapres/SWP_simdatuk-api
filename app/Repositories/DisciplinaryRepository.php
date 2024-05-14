<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DisciplinaryRepository
{
    public function getDetail($userId)
    {
        $userDisciplinaries = DB::table('user_disciplinaries as ud');
        $userDisciplinaries->join('disciplinaries as d', 'ud.disciplinary_id', '=', 'd.id');
        $userDisciplinaries->join('disciplinary_types as dt', 'ud.disciplinary_type_id', '=', 'dt.id');
        $userDisciplinaries->where('ud.user_id', $userId);
        $userDisciplinaries->select(
            'd.period_month',
            'd.period_year',
            'ud.grade',
            'ud.position',
            'dt.id as disciplinary_type_id',
            'dt.name as disciplinary_type_name',
            'dt.description as disciplinary_type_description',
            'dt.performance_allowance_deduction',
            'dt.performance_allowance_duration',
            'ud.decree_number',
            'ud.date_of_decree',
            'ud.start_date',
            'ud.end_date',
            'ud.authorizing_officer',
            'ud.name_of_authorizing_officer',
            'ud.description',
            'ud.created_at'
        );
        return $userDisciplinaries = $userDisciplinaries->get();
    }
}
