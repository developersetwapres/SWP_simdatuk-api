<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TargetRepository
{
    public function getDetail($userId)
    {
        $targets = DB::table('user_targets as ut');
        $targets->join('targets as t', 't.id', '=', 'ut.target_id');
        $targets->where('ut.user_id', $userId);
        $targets->select(
            't.period_month',
            't.period_year',
            't.name',
            't.appraisal_period',
            't.year',
            'ut.work_behavior_rating',
            'ut.employee_performance_predicate',
            'ut.organizational_performance_achievement',
        );
        $targets->orderBy('t.period_year', 'desc');
        $targets->orderBy('t.period_month', 'desc');
        return $targets = $targets->get();
    }
}
