<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecognitionRepository
{
    public function getDetail($userId)
    {
        $recognitions = DB::table('user_recognitions as ur');
        $recognitions->join('recognitions as r', 'r.id', '=', 'ur.recognition_id');
        $recognitions->leftJoin('decrees as d', 'r.type_of_decree', '=', 'd.id');
        $recognitions->where('ur.user_id', $userId);
        $recognitions->select(
            'r.period_month',
            'r.period_year',
            'r.name',
            'r.description',
            'r.type_of_decree',
            'd.name as type_of_decree_name',
            'r.decree_date',
            'r.decree_number',
            'r.decree_year',
            'r.awarding_institution',
            'r.date_of_receipt',
        );
        $recognitions->orderBy('r.period_year', 'desc');
        $recognitions->orderBy('r.period_month', 'desc');
        return $recognitions = $recognitions->get();
    }
}
