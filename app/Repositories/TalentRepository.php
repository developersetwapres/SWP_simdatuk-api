<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class TalentRepository
{
    use Document;

    public function getDetail($userId)
    {
        $talents = DB::table('user_talents');
        $talents->where('user_id', $userId);
        $talents->select(
            'id',
            DB::raw("DATE_FORMAT(event_date, '%d-%m-%Y') as event_date"),
            'point',
            'organizer',
            'talent_document'
        );
        $talents->orderBy('event_date', 'desc');
        $talents = $talents->get();
        foreach ($talents as $talent) {
            $talent->talent_document = $this->getDocument($talent->talent_document);
        }
        return $talents;
    }

    public function getDetailBulkUser($usersID)
    {
        $talents = DB::table('user_talents');
        $talents->whereIn('user_id', $usersID);
        $talents->select(
            'id',
            'user_id',
            DB::raw("DATE_FORMAT(event_date, '%d-%m-%Y') as event_date"),
            'point',
            'organizer',
            'talent_document'
        );
        $talents->orderBy('event_date', 'desc');
        $talents = $talents->get();

        $newTalents = [];
        foreach ($talents as $talent) {
            $talent->talent_document = $this->getDocument($talent->talent_document);
            $newTalents[$talent->user_id][] = $talent;
        }
        return $newTalents;
    }
}
