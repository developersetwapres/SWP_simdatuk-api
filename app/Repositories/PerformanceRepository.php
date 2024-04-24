<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PerformanceRepository
{
    public function getDetail($userId)
    {
        $performances = DB::table('user_performances');
        $performances->where('user_id', $userId);
        $performances->select(
            'period_month',
            'period_year',
            'ppk_period',
            'work_performance_score',
            'description',
        );
        return $performances->get();
    }
}
