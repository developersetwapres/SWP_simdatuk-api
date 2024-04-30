<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NoteRepository
{
    public function getDetail($userId)
    {
        $userNotes = DB::table('user_notes');
        $userNotes->where('user_id', $userId);
        $userNotes->select(
            'description',
        );
        return $userNotes = $userNotes->get();
    }
}
