<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PositionRepository
{
    public function getDetail($userId)
    {
        $userPositions = DB::table('user_positions');
        $userPositions->where('user_id', $userId);
        $userPositions->select(
            'period_month',
            'period_year',
            'position_id',
            'group_id',
            'effective_date',
            'decree',
            'decree_document',
            'type_of_decree',
            'decree_number',
            'decree_date',
            'echelon_description',
            'description',
            'termination_decree',
            'type_of_termination_decree',
            'termination_decree_number',
            'termination_decree_date',
            'status',
        );
        return $userPositions = $userPositions->get();
    }
}
