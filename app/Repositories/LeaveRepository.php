<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LeaveRepository
{
    public function getDetail($userId)
    {
        $leaves = DB::table('user_leaves');
        $leaves->where('user_id', $userId);
        $leaves->select(
            'grade',
            'position',
            'start_date',
            'end_date',
            'reason',
            'number',
            'purpose',
            'leave_letter',
        );
        return $leaves = $leaves->get();
    }
}
