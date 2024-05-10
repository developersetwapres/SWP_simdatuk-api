<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PerformanceRepository
{
    public function getDetail($userId)
    {
        $userPerformances = DB::table('user_performances as up');
        $userPerformances->join('performances as p', 'p.id', '=', 'up.performance_id');
        $userPerformances->where('up.user_id', $userId);
        $userPerformances->select(
            'p.period_month',
            'p.period_year',
            'p.performance_period',
            'p.description',
            'up.work_performance_score',
        );
        return $userPerformances = $userPerformances->get();
    }
}
