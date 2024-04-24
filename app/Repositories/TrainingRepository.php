<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TrainingRepository
{
    public function getDetail($userId, $type)
    {
        $userTrainings = DB::table('user_trainings');
        $userTrainings->where('user_id', $userId);
        $userTrainings->where('type', $type);
        $userTrainings->select(
            'period_month',
            'period_year',
            'name',
            'reference_number',
            'level',
            'start_date',
            'duration',
            'organizer',
            'certificate'
        );
        return $userTrainings = $userTrainings->get();
    }
}
