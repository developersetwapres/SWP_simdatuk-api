<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class AssessmentRepository
{
    use Document;

    public function getDetail($userId)
    {
        $assessements = DB::table('user_assessments');
        $assessements->where('user_id', $userId);
        $assessements->select(
            'id',
            'event_date',
            'point',
            'organizer',
            'assessment_document'
        );
        $assessements->orderBy('event_date', 'desc');
        $assessements = $assessements->get();
        foreach ($assessements as $assessment) {
            $assessment->assessment_document = $this->getDocument($assessment->assessment_document);
        }
        return $assessements;
    }
}
