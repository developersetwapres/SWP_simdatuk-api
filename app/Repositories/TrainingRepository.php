<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class TrainingRepository
{
    use Document;

    public function getDetail($userId, $type)
    {
        $trainings = DB::table('user_trainings as ut');
        $trainings->join('trainings as t', 't.id', '=', 'ut.training_id');
        $trainings->where('ut.user_id', $userId);
        $trainings->where('t.type', $type);
        $trainings->select(
            'ut.id',
            't.period_month',
            't.period_year',
            't.name',
            't.level',
            't.start_date',
            't.duration',
            't.organizer',
            't.reference_number',
            't.link',
            'ut.certificate',
            't.type',
        );
        $trainings->orderBy('t.period_year', 'desc');
        $trainings->orderBy('t.period_month', 'desc');
        $trainings = $trainings->get();

        foreach ($trainings as $training) {
            $training->certificate = $this->getDocument($training->certificate);
        }

        return $trainings;
    }
}
