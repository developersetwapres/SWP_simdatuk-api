<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecapitulationRepository
{

    /**
     * Get total grade by type of grade
     *
     * @param $type
     * @return void
     */
    public function getGrade($type)
    {
        $grade = DB::table('grades as g');
        $grade->join('users as u', 'u.grade_id', '=', 'g.id');
        $grade->select(
            'g.name',
            'g.code',
            DB::raw('COUNT(u.id) as total')
        );
        $grade->where('g.type', $type);
        $grade->whereIn('u.employment_status', [1, 6]);
        $grade->groupBy('u.grade_id');
        $grade->orderBy('g.id', 'asc');
        $grade = $grade->get();
        $total = $grade->count();
        return array($total, $grade);
    }

    /**
     * Get list of outsource position and total by type of employment
     *
     * @param int $type
     * @return void
     */
    public function getOutsource($type)
    {
        $positions = DB::table('positions as p');
        $positions->select(
            'p.id',
            'p.name',
            DB::raw('COUNT(u.id) as total'),
        );
        $positions->join('users as u', 'u.position_id', '=', 'p.id');
        $positions->where('p.type', 3);
        $positions->where('u.employment_status', 1);
        $positions->where('u.employment_type_id', $type);
        $positions->orderBy('p.id', 'asc');
        $positions->groupBy('p.id', 'p.name');
        $positions = $positions->get();
        $total = $positions->sum('total');
        return array($total, $positions);
    }

    /**
     * Get total of education and gender by type of employee
     *
     * @param int $type
     * @return void
     */
    public function getEducationAndGender($type)
    {
        $total = DB::table('users');
        $total->select(
            DB::raw('COUNT(CASE WHEN gender IS NOT NULL THEN 1 END) as total_gender'),
            DB::raw('COUNT(CASE WHEN gender = 0 THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN gender = 1 THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN education_level IS NOT NULL THEN 1 END) as total_education'),
            DB::raw('COUNT(CASE WHEN education_level = 1 THEN 1 END) as sd'),
            DB::raw('COUNT(CASE WHEN education_level = 2 THEN 1 END) as smp'),
            DB::raw('COUNT(CASE WHEN education_level = 3 THEN 1 END) as sma'),
            DB::raw('COUNT(CASE WHEN education_level = 4 THEN 1 END) as d1'),
            DB::raw('COUNT(CASE WHEN education_level = 5 THEN 1 END) as d3'),
            DB::raw('COUNT(CASE WHEN education_level = 6 THEN 1 END) as s1'),
            DB::raw('COUNT(CASE WHEN education_level = 7 THEN 1 END) as s2'),
            DB::raw('COUNT(CASE WHEN education_level = 8 THEN 1 END) as s3'),
        );
        $total->where('type', $type);
        $total->whereIn('employment_status', [1]);
        return $total = $total->first();
    }

    /**
     * Get Total TIM TPPS
     *
     * @param int $type
     * @return void
     */
    public function getTim($type)
    {
        $users = DB::table('users');
        $users->where('employment_type_id', $type);
        $users->where('employment_status', 1);
        return $users = $users->count();
    }

    /**
     * Get non active ASN
     *
     * @return void
     */
    public function getNonActiveAsn()
    {
        $users = DB::table('users');
        $users->select(
            DB::raw('COUNT(CASE WHEN employment_status IN (7, 8, 9) && type = 1 THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 7 THEN 1 END) as cltn'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 8 THEN 1 END) as tbln'),
            DB::raw('COUNT(CASE WHEN type = 1 && employment_status = 9 THEN 1 END) as nonactive'),
        );
        return $users = $users->first();
    }
}
