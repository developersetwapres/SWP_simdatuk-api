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
            DB::raw("DATE_FORMAT(event_date, '%d-%m-%Y') as event_date"),
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

    public function getDetailBulkUser($usersID)
    {
        $assessements = DB::table('user_assessments');
        $assessements->whereIn('user_id', $usersID);
        $assessements->select(
            'id',
            'user_id',
            DB::raw("DATE_FORMAT(event_date, '%d-%m-%Y') as event_date"),
            'point',
            'organizer',
            'assessment_document'
        );
        $assessements->orderBy('event_date', 'desc');
        $assessements = $assessements->get();

        $newAssessment = [];
        foreach ($assessements as $assessment) {
            $assessment->assessment_document = $this->getDocument($assessment->assessment_document);
            $newAssessment[$assessment->user_id][] = $assessment;
        }

        return $newAssessment;
    }
}
