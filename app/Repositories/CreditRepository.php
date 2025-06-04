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
            'uc.score',
            'uc.start_month',
            'uc.end_month',
        );
        $credits->orderBy('year', 'desc');
        $credits->orderBy('period', 'desc');
        return $credits = $credits->get();
    }

    public function getDetailBulkUser($usersID)
    {
        $credits = DB::table('user_credits as uc');
        $credits->join('users as u', 'uc.user_id', '=', 'u.id');
        $credits->whereIn('uc.user_id', $usersID);
        $credits->select(
            'uc.id',
            'uc.user_id',
            'uc.position',
            'uc.period',
            'uc.year',
            'uc.score',
            'uc.start_month',
            'uc.end_month',
        );
        $credits->orderBy('year', 'desc');
        $credits->orderBy('period', 'desc');
        $credits = $credits->get();

        $newCredits = [];
        foreach ($credits as $key => $credit) {
            $newCredits[$credit->user_id][] = $credit;
        }

        return $newCredits;
    }
}
