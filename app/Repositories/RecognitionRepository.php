<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecognitionRepository
{
    public function getDetail($userId)
    {
        $userRecognitions = DB::table('user_recognitions as ur');
        $userRecognitions->join('recognitions as r', 'r.id', '=', 'ur.recognition_id');
        $userRecognitions->where('ur.user_id', $userId);
        $userRecognitions->select(
            'r.period_month',
            'r.period_year',
            'r.name',
            'r.description',
            'r.type_of_decree',
            'r.decree_date',
            'r.decree_number',
            'r.decree_year',
            'r.awarding_institution',
            'r.date_of_receipt',
            'r.created_at'
        );
        return $userRecognitions = $userRecognitions->get();
    }
}
