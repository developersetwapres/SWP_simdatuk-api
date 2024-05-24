<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AssessmentRepository
{
    public function getDetail($userId, $type)
    {
        $assessements = DB::table('user_assessments');
        $assessements->where('user_id', $userId);
        $assessements->where('type', $type);
        $assessements->select(
            'assessment_date',
            'point',
            'organizer',
            'assessment_document',
            'type',
        );
        return $assessements = $assessements->get();
    }
}
