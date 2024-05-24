<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PerformanceRepository
{
    public function getDetail($userId)
    {
        $performances = DB::table('user_performances as up');
        $performances->join('performances as p', 'p.id', '=', 'up.performance_id');
        $performances->where('up.user_id', $userId);
        $performances->select(
            'p.period_month',
            'p.period_year',
            'p.name',
            'p.performance_period',
            'p.description',
            'up.work_performance_score',
        );
        $performances->orderBy('p.period_year', 'desc');
        $performances->orderBy('p.period_month', 'desc');
        return $performances = $performances->get();
    }
}
