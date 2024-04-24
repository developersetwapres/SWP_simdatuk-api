<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LeaveRepository
{
    public function getDetail($userId)
    {
        $userLeaves = DB::table('user_leaves');
        $userLeaves->where('user_id', $userId);
        $userLeaves->select(
            'grade_id',
            'position',
            'start_date',
            'end_date',
            'reason',
            'number',
            'purpose',
            'leave_letter',
        );
        return $userLeaves = $userLeaves->get();
    }
}
