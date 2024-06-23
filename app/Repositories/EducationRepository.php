<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class EducationRepository
{
    public function getDetail($userId)
    {
        $educations = DB::table('user_educations');
        $educations->where('user_id', $userId);
        $educations->select(
            'id',
            'level',
            'name',
            'faculty',
            'major',
            'status',
            'year_of_graduation',
            'description'
        );
        $educations->orderBy('level', 'desc');
        $educations->orderBy('year_of_graduation', 'desc');
        return $educations = $educations->get();
    }
}
