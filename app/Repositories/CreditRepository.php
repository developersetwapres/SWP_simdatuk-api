<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CreditRepository
{
    public function getDetail($userId)
    {
        $credits = DB::table('user_credits as uc');
        $credits->join('users as u', 'uc.user_id', '=', 'u.id');
        $credits->where('uc.user_id', $userId);
        $credits->select(
            'uc.id',
            'uc.position',
            'uc.period',
            'uc.year',
            'uc.score'
        );
        $credits->orderBy('year', 'desc');
        $credits->orderBy('period', 'desc');
        return $credits = $credits->get();
    }
}
