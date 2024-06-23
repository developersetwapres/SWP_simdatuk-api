<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DisciplinaryRepository
{
    public function getDetail($userId)
    {
        $disciplinaries = DB::table('disciplinary_history_users as dhu');
        $disciplinaries->join('disciplinary_histories as dh', 'dhu.disciplinary_history_id', '=', 'dh.id');
        $disciplinaries->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id');
        $disciplinaries->where('dhu.user_id', $userId);
        $disciplinaries->select(
            'dhu.id',
            'dh.period_month',
            'dh.period_year',
            'dhu.grade',
            'dhu.position',
            'd.id as disciplinary_id',
            'd.name as disciplinary_name',
            'd.description as disciplinary_description',
            'd.performance_allowance_deduction',
            'd.performance_allowance_duration',
            'dhu.decree_number',
            'dhu.date_of_decree',
            'dhu.start_date',
            'dhu.end_date',
            'dhu.authorizing_officer',
            'dhu.name_of_authorizing_officer',
            'dhu.description'
        );
        $disciplinaries->orderBy('dhu.date_of_decree', 'desc');
        return $disciplinaries = $disciplinaries->get();
    }
}
