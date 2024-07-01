<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FamilyRepository
{
    public function getDetail($userId)
    {
        $families = DB::table('user_families');
        $families->where('user_id', $userId);
        $families->select(
            'id',
            'card_number',
            'name',
            'id_number',
            'gender',
            'religion',
            'date_of_birth',
            'place_of_birth',
            'name_of_father',
            'name_of_mother',
            'relationship_status',
            'education',
            'occupation',
            'occupation_description',
            'marital_status',
            'mobile_phone',
            'sequence_number',
        );
        $families->orderBy('sequence_number', 'asc');
        return $families = $families->get();
    }
}
