<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TargetRepository
{
    public function getDetail($userId)
    {
        $targets = DB::table('target_histories as th');
        $targets->join('target_history_users as thu', 'th.id', '=', 'thu.target_history_id');
        $targets->where('thu.user_id', $userId);
        $targets->select(
            'thu.id',
            'th.period_month',
            'th.period_year',
            'th.name',
            'th.appraisal_period',
            'th.year',
            'thu.work_behavior_rating',
            'thu.employee_performance_predicate',
            'thu.organizational_performance_achievement',
        );
        $targets->orderBy('th.period_year', 'desc');
        $targets->orderBy('th.period_month', 'desc');
        return $targets = $targets->get();
    }
}
