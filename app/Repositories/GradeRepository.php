<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class GradeRepository
{
    use Document;

    public function getDetail($userId)
    {
        $grades = DB::table('grade_history_users as ghu');
        $grades->join('grades as g', 'ghu.grade_id', '=', 'g.id');
        $grades->where('ghu.user_id', $userId);
        $grades->select(
            'ghu.id',
            'g.id as grade_id',
            'g.name as grade_name',
            'g.code as grade_code',
            'ghu.effective_date',
            'ghu.decree_name',
            'ghu.decree_name',
            'ghu.decree_document',
            'ghu.type_of_decree',
            'ghu.decree_number',
            'ghu.decree_date',
            'ghu.description',
            'ghu.status',
            'ghu.created_at'
        );
        $grades = $grades->get();

        foreach ($grades as $grade) {
            $grade->decree_document = $this->getDocument($grade->decree_document);
        }

        return $grades;
    }
}
