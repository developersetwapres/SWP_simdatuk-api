<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class TrainingRepository
{
    use Document;

    public function getDetail($userId, $type)
    {
        $trainings = DB::table('training_histories as th');
        $trainings->join('training_history_users as thu', 'th.id', '=', 'thu.training_history_id');
        $trainings->where('thu.user_id', $userId);
        $trainings->where('th.type', $type);
        $trainings->select(
            'thu.id',
            'th.period_month',
            'th.period_year',
            'th.name',
            'th.level',
            'th.start_date',
            'th.duration',
            'th.organizer',
            'th.reference_number',
            'th.link',
            'thu.certificate',
            'th.type',
        );
        $trainings->orderBy('th.start_date', 'desc');
        $trainings = $trainings->get();
        foreach ($trainings as $training) {
            $training->certificate = $this->getDocument($training->certificate);
        }
        return $trainings;
    }
}
