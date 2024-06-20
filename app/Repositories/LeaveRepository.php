<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class LeaveRepository
{
    use Document;

    public function getDetail($userId)
    {
        $leaves = DB::table('user_leaves');
        $leaves->where('user_id', $userId);
        $leaves->select(
            'start_date',
            'end_date',
            'type',
            'number',
            'description',
            'letter',
        );
        $leaves = $leaves->get();

        foreach ($leaves as $leave) {
            $leave->letter = $this->getDocument($leave->letter);
        }

        return $leaves;
    }
}
