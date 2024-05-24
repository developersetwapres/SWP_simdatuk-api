<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DisciplinaryRepository
{
    public function getDetail($userId)
    {
        $disciplinaries = DB::table('disciplinary_history_users as ud');
        $disciplinaries->join('disciplinary_histories as d', 'ud.disciplinary_history_id', '=', 'd.id');
        $disciplinaries->join('disciplinaries as dt', 'ud.disciplinary_id', '=', 'dt.id');
        $disciplinaries->where('ud.user_id', $userId);
        $disciplinaries->select(
            'd.period_month',
            'd.period_year',
            'ud.grade',
            'ud.position',
            'dt.id as disciplinary_id',
            'dt.name as disciplinary_name',
            'dt.description as disciplinary_description',
            'dt.performance_allowance_deduction',
            'dt.performance_allowance_duration',
            'ud.decree_number',
            'ud.date_of_decree',
            'ud.start_date',
            'ud.end_date',
            'ud.authorizing_officer',
            'ud.name_of_authorizing_officer',
            'ud.description'
        );
        $disciplinaries->orderBy('d.period_year', 'desc');
        $disciplinaries->orderBy('d.period_month', 'desc');
        return $disciplinaries = $disciplinaries->get();
    }
}
