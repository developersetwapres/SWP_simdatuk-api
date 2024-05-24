<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class PositionRepository
{
    use Document;

    public function getDetail($userId)
    {
        $positions = DB::table('position_history_users as phu');
        $positions->join('users as u', 'u.id', '=', 'phu.user_id');
        $positions->join('position_histories as ph', 'phu.position_history_id', '=', 'ph.id');
        $positions->leftjoin('groups as g', 'phu.group_id', '=', 'g.id');
        $positions->leftjoin('echelons as e', 'phu.echelon_id', '=', 'e.id');
        $positions->leftjoin('decrees as tod', 'phu.type_of_decree', '=', 'tod.id');
        $positions->leftjoin('decrees as totd', 'phu.type_of_termination_decree', '=', 'totd.id');
        $positions->where('phu.user_id', $userId);
        $positions->select(
            'phu.id',
            'ph.period_month',
            'ph.period_year',
            'phu.position',
            'g.id as group_id',
            'g.name as group_name',
            'e.id as echelon_id',
            'e.name as echelon_name',
            'phu.position_status',
            'phu.effective_date',
            'phu.decree',
            'phu.decree_document',
            'phu.decree_number',
            'tod.id as type_decree_id',
            'tod.name as type_decree_name',
            'totd.id as type_termination_decree_id',
            'totd.name as type_termination_decree_name',
            'phu.decree_date',
            'phu.termination_date',
            'phu.termination_decree',
            'phu.termination_decree_number',
            'phu.termination_decree_date',
            'phu.status'
        );
        $positions->orderBy('ph.period_year', 'desc');
        $positions->orderBy('ph.period_month', 'desc');
        $positions = $positions->get();

        foreach ($positions as $position) {
            $position->decree_document = $this->getDocument($position->decree_document);
        }
        return $positions;
    }
}
