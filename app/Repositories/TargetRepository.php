<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TargetRepository
{
    public function getDetail($userId)
    {
        $userTargets = DB::table('user_targets as ut');
        $userTargets->join('targets as t', 't.id', '=', 'ut.target_id');
        $userTargets->where('ut.user_id', $userId);
        $userTargets->select(
            't.period_month',
            't.period_year',
            't.appraisal_period',
            't.year',
            'ut.work_behavior_rating',
            'ut.employee_performance_predicate',
            'ut.organizational_performance_achievement',
            't.name',
            't.created_at');
        return $userTargets = $userTargets->get();
    }
}
