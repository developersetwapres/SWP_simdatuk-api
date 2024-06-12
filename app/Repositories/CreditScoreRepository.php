<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CreditScoreRepository
{
    public function getDetail($userId)
    {
        $creditscore = DB::table('user_credit_score as ucs');
        $creditscore->join('users', 'ucs.user_id', '=', 'users.id');
        $creditscore->where('user_id', $userId);
        $creditscore->select(
            'ucs.position',
            'ucs.period',
            'ucs.year',
            'ucs.last_credit_score',
            'users.name',
        );
        return $creditscore = $creditscore->get();
    }
}
