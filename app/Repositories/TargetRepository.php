<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TargetRepository
{
    public function getDetail($userId)
    {
        $target = DB::table('user_targets as ut');
        $target->join('targets as t', 't.id', '=', 'ut.target_id');
        $target->where('ut.user_id', $userId);
        $target->select('t.id',
            't.period_month',
            't.period_year',
            't.appraisal_period',
            't.year',
            'ut.work_behavior_rating',
            'ut.employee_performance_predicate',
            'ut.organizational_performance_achievement',
            't.name',
            't.created_at');
        $target = $target->get();
        return $userTargets = $target->get();
    }
}
