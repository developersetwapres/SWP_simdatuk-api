<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class EducationRepository
{
    use Document;

    public function getDetail($userId)
    {
        $educations = DB::table('user_educations')
            ->where('user_id', $userId)
            ->select(
                'id',
                'level',
                'name',
                'faculty',
                'major',
                'status',
                'year_of_graduation',
                'description',
                'degree_document',
            )
            ->orderBy('level', 'desc')
            ->orderBy('year_of_graduation', 'desc')->get();


        foreach ($educations as $education) {
            $education->degree_document = $this->getDocument($education->degree_document);
        }
        return $educations;
    }
}
