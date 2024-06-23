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
            'id',
            'description',
        );
        $notes->orderBy('created_at', 'desc');
        return $notes = $notes->get();
    }
}
