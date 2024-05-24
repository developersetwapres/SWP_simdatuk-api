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
            'level',
            'name',
            'faculty',
            'major',
            'status',
            'year_of_graduation',
            'description'
        );
        return $educations = $educations->get();
    }
}
