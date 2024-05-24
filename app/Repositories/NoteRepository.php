<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NoteRepository
{
    public function getDetail($userId)
    {
        $notes = DB::table('user_notes');
        $notes->where('user_id', $userId);
        $notes->select(
            'description',
        );
        return $notes = $notes->get();
    }
}
