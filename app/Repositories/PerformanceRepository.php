<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PerformanceRepository
{
    public function getDetail($userId)
    {
        $performances = DB::table('performance_histories as ph');
        $performances->join('performance_history_users as phu', 'ph.id', '=', 'phu.performance_history_id');
        $performances->where('phu.user_id', $userId);
        $performances->select(
            'phu.id',
            'ph.period_month',
            'ph.period_year',
            'ph.name',
            'ph.performance_period',
            'phu.work_performance_score',
            'phu.description',
        );
        $performances->orderBy('ph.period_year', 'desc');
        $performances->orderBy('ph.period_month', 'desc');
        return $performances = $performances->get();
    }

    public function getDetailBulkUser($usersID)
    {
        $performances = DB::table('performance_histories as ph');
        $performances->join('performance_history_users as phu', 'ph.id', '=', 'phu.performance_history_id');
        $performances->whereIn('phu.user_id', $usersID);
        $performances->select(
            'phu.id',
            'phu.user_id',
            'ph.period_month',
            'ph.period_year',
            'ph.name',
            'ph.performance_period',
            'phu.work_performance_score',
            'phu.description',
        );
        $performances->orderBy('ph.period_year', 'desc');
        $performances->orderBy('ph.period_month', 'desc');
        $performances = $performances->get();

        $newPerformances = [];
        foreach ($performances as $performance) {
            $newPerformances[$performance->user_id][] = $performance;
        }

        return $newPerformances;
    }
}
