<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecognitionRepository
{
    public function getDetail($userId)
    {
        $userRecognitions = DB::table('user_recognitions');
        $userRecognitions->where('user_id', $userId);
        $userRecognitions->select(
            'period_month',
            'period_year',
            'name',
            'description',
            'type_of_decree',
            'decree_date',
            'decree_number',
            'decree_year',
            'awarding_institution',
            'date_of_receipt'
        );
        return $userRecognitions = $userRecognitions->get();
    }
}
