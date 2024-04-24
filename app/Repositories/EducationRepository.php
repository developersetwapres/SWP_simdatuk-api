<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class EducationRepository
{
    public function getDetail($userId)
    {
        $userEducations = DB::table('user_educations');
        $userEducations->where('user_id', $userId);
        $userEducations->select(
            'level',
            'name',
            'faculty',
            'major',
            'status',
            'year_of_graduation',
            'description'
        );
        return $userEducations = $userEducations->get();
    }
}
