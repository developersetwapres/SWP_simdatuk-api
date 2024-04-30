<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AssessmentRepository
{
    public function getDetail($userId, $type)
    {
        $userAssessments = DB::table('user_assessments');
        $userAssessments->where('user_id', $userId);
        $userAssessments->select(
            'assessment_date',
            'point',
            'organizer',
            'assessment_document',
            'type',
        );
        return $userAssessments = $userAssessments->get();
    }
}
