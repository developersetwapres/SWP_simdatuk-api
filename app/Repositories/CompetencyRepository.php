<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class CompetencyRepository
{
    use Document;

    public function getDetail($userId)
    {
        $competencies = DB::table('user_competencies');
        $competencies->where('user_id', $userId);
        $competencies->select(
            'id',
            DB::raw("DATE_FORMAT(event_date, '%d-%m-%Y') as event_date"),
            'point',
            'organizer',
            'competency_document'
        );
        $competencies->orderBy('event_date', 'desc');
        $competencies = $competencies->get();
        foreach ($competencies as $competency) {
            $competency->competency_document = $this->getDocument($competency->competency_document);
        }
        return $competencies;
    }
}
