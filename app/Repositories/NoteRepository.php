<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NoteRepository
{
    public function getDetail($userId)
    {
        $notes = DB::table('user_notes as un')
            ->leftJoin('users as u', 'un.giver_id', '=', 'u.id')
            ->where('un.user_id', $userId)
            ->select(
                'un.id',
                'un.description',
                'u.name as giver_name',
                DB::raw("DATE_FORMAT(un.created_at, '%d-%m-%Y') as created_at"),
            )
            ->orderBy('un.created_at', 'desc');
        return $notes = $notes->get();
    }
}
