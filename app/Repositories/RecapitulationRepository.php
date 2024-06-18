<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecapitulationRepository
{

    /**
     * Get total of pejabat pimpinan
     *
     * @return void
     */
    public function getPejabatPimpinanAndFungsional()
    {
        $pejabat = DB::table('users as u');
        $pejabat->select(
            DB::raw('COUNT(CASE WHEN echelon_id IN (1,2,3,4) THEN 1 END) AS total_pejabat_pimpinan'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (1) THEN 1 END) AS echelon1'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (2) THEN 1 END) AS echelon2'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (3) THEN 1 END) AS echelon3'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (4) THEN 1 END) AS echelon4'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (5,6,7,8) THEN 1 END) AS total_pejabat_fungsional_keahlian'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (5) THEN 1 END) AS ahli_utama'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (6) THEN 1 END) AS ahli_madya'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (7) THEN 1 END) AS ahli_muda'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (8) THEN 1 END) AS ahli_pertama'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (10,11,12,13) THEN 1 END) AS total_pejabat_fungsional_keterampilan'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (10) THEN 1 END) AS penyelia'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (11) THEN 1 END) AS mahir'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (12) THEN 1 END) AS terampil'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (13) THEN 1 END) AS pemula'),
        );
        $pejabat->where('u.employment_status', 1);
        $pejabat->where('u.type', 1);
        return $pejabat = $pejabat->first();
    }

    /**
     * Get total pejabat pelaksana
     */
    public function getPejabatPelaksana()
    {
        $pelaksana = DB::table('users');
        $pelaksana->select(
            DB::raw('COUNT(CASE WHEN echelon_id IN (9) THEN 1 END) AS total'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (9) && grade_id IN (1,2,3,4,5) && employment_type_id != 1 THEN 1 END) AS golongan4'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (9) && grade_id IN (6,7,8,9) && employment_type_id != 1 THEN 1 END) AS golongan3'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (9) && grade_id IN (10,11,12,13) && employment_type_id != 1 THEN 1 END) AS golongan2'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (9) && employment_type_id = 1 THEN 1 END) AS tnipolri'),
        );
        $pelaksana->where('employment_status', 1);
        $pelaksana->where('type', 1);
        return $pelaksana = $pelaksana->first();
    }

    /**
     * Get keterangan jabatan
     *
     * @return void
     */
    public function getKeteranganJabatan()
    {
        $pejabat = DB::table('users as u');
        $pejabat->select(
            DB::raw('COUNT(CASE WHEN echelon_id IS NOT NULL THEN 1 END) AS total_pejabat_pimpinan'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (1,2) THEN 1 END) AS jabatan_pimpinan_tinggi'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (3,4,9) THEN 1 END) AS jabatan_administrasi'),
            DB::raw('COUNT(CASE WHEN echelon_id IN (5,6,7,8,10,11,12,13) THEN 1 END) AS jabatan_fungsional'),
        );
        $pejabat->where('u.employment_status', 1);
        $pejabat->where('u.type', 1);
        return $pejabat = $pejabat->first();
    }

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
