<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TargetRepository
{
    public function getDetail($userId)
    {
        $userTargets = DB::table('user_targets');
        $userTargets->where('user_id', $userId);
        $userTargets->select(
            'period_month',
            'period_year',
            'appraisal_period',
            'year',
            'work_behavior_rating',
            'employee_performance_predicate',
            'organizational_performance_achievement',
        );
        return $userTargets = $userTargets->get();
    }
}
