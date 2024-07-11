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
            'id',
            DB::raw("DATE_FORMAT(start_date, '%d-%m-%Y') as start_date"),
            DB::raw("DATE_FORMAT(end_date, '%d-%m-%Y') as end_date"),
            'type',
            'number',
            'description',
            'letter',
        );
        $leaves->orderBy('start_date', 'desc');
        $leaves = $leaves->get();
        foreach ($leaves as $leave) {
            $leave->letter = $this->getDocument($leave->letter);
        }
        return $leaves;
    }
}
