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
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (1,2,3,4) THEN 1 END) AS total_pejabat_pimpinan'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (1) THEN 1 END) AS echelon1'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (2) THEN 1 END) AS echelon2'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (3) THEN 1 END) AS echelon3'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (4) THEN 1 END) AS echelon4'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (5,6,7,8) THEN 1 END) AS total_pejabat_fungsional_keahlian'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (5) THEN 1 END) AS ahli_utama'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (6) THEN 1 END) AS ahli_madya'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (7) THEN 1 END) AS ahli_muda'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (8) THEN 1 END) AS ahli_pertama'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (10,11,12,13) THEN 1 END) AS total_pejabat_fungsional_keterampilan'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (10) THEN 1 END) AS penyelia'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (11) THEN 1 END) AS mahir'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (12) THEN 1 END) AS terampil'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (13) THEN 1 END) AS pemula'),
        );
        return $pejabat = $pejabat->first();
    }

    /**
     * Get total pejabat pelaksana
     */
    public function getPejabatPelaksana()
    {
        $pelaksana = DB::table('users');
        $pelaksana->select(
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (9) THEN 1 END) AS total'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (9) AND employment_type_id != 1 AND grade_id IN (1,2,3,4,5) THEN 1 END) AS golongan4'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (9) AND employment_type_id != 1 AND grade_id IN (6,7,8,9) THEN 1 END) AS golongan3'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (9) AND employment_type_id != 1 AND grade_id IN (10,11,12,13) THEN 1 END) AS golongan2'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (1, 6) AND echelon_id IN (9) AND employment_type_id = 1 THEN 1 END) AS tnipolri'),
        );
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
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IS NOT NULL THEN 1 END) AS total_pejabat_pimpinan'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (1,2) THEN 1 END) AS jabatan_pimpinan_tinggi'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (3,4,9) THEN 1 END) AS jabatan_administrasi'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (5,6,7,8,10,11,12,13) THEN 1 END) AS jabatan_fungsional'),
        );
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
            DB::raw("g.id"),
            DB::raw("CONCAT(g.name, ' ', g.code) as name"),
            DB::raw('COUNT(u.id) as total')
        );
        $grade->where('g.type', $type);
        $grade->whereIn('u.employment_status', [1, 6]);
        $grade->groupBy('u.grade_id');
        $grade->orderBy('g.id', 'asc');
        $grade = $grade->get();
        $total = $grade->sum('total');
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
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND gender IS NOT NULL THEN 1 END) as total_gender'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND gender = 0 THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND gender = 1 THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level IS NOT NULL THEN 1 END) as total_education'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 1 THEN 1 END) as sd'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 2 THEN 1 END) as smp'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 3 THEN 1 END) as sma'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 4 THEN 1 END) as d1'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 5 THEN 1 END) as d3'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 6 THEN 1 END) as s1'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 7 THEN 1 END) as s2'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND education_level = 8 THEN 1 END) as s3'),
        );
        $total->where('type', $type);
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
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status IN (7, 8, 9) THEN 1 END) as total'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status = 7 THEN 1 END) as cltn'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status = 8 THEN 1 END) as tbln'),
            DB::raw('COUNT(CASE WHEN type = 1 AND employment_status = 9 THEN 1 END) as nonactive'),
        );
        return $users = $users->first();
    }

    /**
     * Get total unit kerja
     *
     * @return void
     */
    public function getTotalUnitKerja()
    {
        $positions = DB::table('positions');
        $positions->select('id', 'name');
        $positions->where('parent_id', 2);
        $positions->orderBy('id', 'asc');
        $positions = $positions->get();
        $newItem = (object) ['id' => 4, 'name' => 'Kementerian Sekretariat Negara'];
        $positions->push($newItem);

        $data = array();
        foreach ($positions as $position) {
            $sql = "
                WITH RECURSIVE hierarchy AS (
                    -- Anchor member: Select the initial parent row
                    SELECT
                        po.id,
                        po.name,
                        po.parent_id
                    FROM
                        positions po
                    WHERE
                        po.id = '$position->id' -- Replace ? with the specific parent id

                    UNION ALL

                    -- Recursive member: Select the child row
                    SELECT
                        p.id,
                        p.name,
                        p.parent_id
                    FROM
                        positions p
                    INNER JOIN
                        hierarchy h ON p.parent_id = h.id
                )
                SELECT
                    COUNT(*) as total
                FROM
                    hierarchy
                JOIN users ON hierarchy.id=users.position_id
                WHERE
                    users.employment_status
                IN
                    (1,6);
            ";
            $total = DB::select($sql);
            $total = $total[0]->total;
            array_push($data, ['id' => $position->id, 'name' => $position->name, 'total' => $total]);
        }
        array_unshift($data, ['id' => 2, 'name' => 'Kepala Sekretariat Wakil Presiden', 'total' => 1]);
        $totalSum = array_reduce($data, function ($carry, $item) {
            return $carry + $item['total'];
        }, 0);
        $data = ["total" => $totalSum, 'data' => $data];
        return $data;
    }

    /**
     * Pimpinan tinggi
     *
     * @return void
     */
    public function getPimpinanTinggi()
    {
        $pejabat = DB::table('users as u');
        $pejabat->select(
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (1,2) THEN 1 END) AS total_jabatan_pimpinan_tinggi'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (1) THEN 1 END) AS jabatan_tinggi_madya'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (2) THEN 1 END) AS jabatan_tinggi_pratama'),
        );
        return $pejabat = $pejabat->first();
    }

    /**
     * Administrasi
     *
     * @return void
     */
    public function getAdministrasi()
    {
        $pejabat = DB::table('users as u');
        $pejabat->select(
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (3,4,9) THEN 1 END) AS total_jabatan_administrasi'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (3) THEN 1 END) AS jabatan_administrasi'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (4) THEN 1 END) AS jabatan_pengawas'),
            DB::raw('COUNT(CASE WHEN employment_status IN (1, 6) AND type = 1 AND echelon_id IN (9) THEN 1 END) AS jabatan_pelaksana'),
        );
        return $pejabat = $pejabat->first();
    }

    /**
     * Jabatan fungsional
     *
     * @return void
     */
    public function getJabatanFungsional()
    {
        $positions = DB::table('positions as p');
        $positions->select(DB::raw('GROUP_CONCAT(id) AS ids'), 'name');
        $positions->where('type', 2);
        $positions->groupBy('name');
        $positions = $positions->get();

        $data = array();
        foreach ($positions as $position) {
            $numbers_array = explode(',', $position->ids);
            $numbers_array = array_map('intval', $numbers_array);
            $positionEchelons = DB::table('users');
            $positionEchelons->join('positions', 'users.position_id', '=', 'positions.id');
            $positionEchelons->join('echelons', 'users.echelon_id', '=', 'echelons.id');
            $positionEchelons->whereIn('positions.id', $numbers_array);
            $positionEchelons->select('echelons.id', 'echelons.name', DB::raw('COUNT(*) as total'));
            $positionEchelons->groupBy('echelons.name');
            $positionEchelons = $positionEchelons->get();
            array_push($data, [
                "id" => $position->ids,
                "name" => $position->name,
                'total' => $positionEchelons->sum('total'),
                "cards" => $positionEchelons,
            ]);
        }

        $totalSum = 0;
        foreach ($data as $item) {
            $totalSum += $item["total"];
        }
        return array($totalSum, $data);
    }

    /**
     * Jabatan Non ASN
     *
     * @return void
     */
    public function getJabatanNonAsn()
    {
        $positions = DB::table('users as u');
        $positions->join('positions as p', 'u.position_id', '=', 'p.id');
        $positions->select(DB::raw('GROUP_CONCAT(p.id) AS id'), 'p.name', DB::raw('COUNT(u.id) as total'));
        $positions->where('u.type', 2);
        $positions->groupBy('p.name');
        return $positions = $positions->get();
    }

    /**
     * Pejabat perbantuan
     *
     * @param int $parentId
     * @return void
     */
    public function getPejabatDiperbantukan($parentId)
    {
        $sql = "
            WITH RECURSIVE hierarchy AS (
                -- Anchor member: Select the initial parent row
                SELECT
                    po.id,
                    po.name,
                    po.parent_id
                FROM
                    positions po
                WHERE
                    po.id = '$parentId' -- Replace ? with the specific parent id

                UNION ALL

                -- Recursive member: Select the child row
                SELECT
                    p.id,
                    p.name,
                    p.parent_id
                FROM
                    positions p
                INNER JOIN
                    hierarchy h ON p.parent_id = h.id
            )
            SELECT
                COUNT(*) AS total,
                COUNT(CASE WHEN echelons.id IN (1,2,3,4) THEN 1 END) AS struktural,
                COUNT(CASE WHEN echelons.id IN (1,2,3,4) THEN 1 END) AS pelaksana,
                COUNT(CASE WHEN echelons.id NOT IN (1,2,3,4,9) THEN 1 END) AS fungsional
            FROM
                hierarchy
            JOIN users ON hierarchy.id=users.position_id
            LEFT JOIN echelons ON users.echelon_id=echelons.id
            WHERE
                users.employment_status
            IN
                (1,6);
        ";
        $users = DB::select($sql);
        return $users[0];
    }
}
