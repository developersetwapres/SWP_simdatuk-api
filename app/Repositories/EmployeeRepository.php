<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Facades\DB;

class EmployeeRepository
{
    use Document;

    public function getDetail($userId)
    {
        $user = DB::table('users as u');
        $user->leftJoin('positions as p', 'u.position_id', '=', 'p.id');
        $user->leftJoin('grades as g', 'u.grade_id', '=', 'g.id');
        $user->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id');
        $user->leftJoin('residences as r', 'u.residence_id', '=', 'r.id');
        $user->where('u.id', $userId);
        $user->select(
            'u.id',
            'u.email',
            'u.name',
            'u.photo_profile',
            'u.employee_id_number',
            'u.employee_registration_number',
            'u.place_of_birth',
            'u.date_of_birth',
            'u.religion',
            'u.gender',
            'u.marital_status',
            'u.employment_type_id',
            'u.grade_id',
            'g.name as grade_name',
            'g.code as grade_code',
            'u.grade_effective_date',
            'u.position_id',
            'p.name as position_name',
            'u.echelon_id',
            'e.name as echelon_name',
            'u.echelon_effective_date',
            'u.institution_id',
            'u.organization_id',
            'u.work_unit_id',
            'u.education_level',
            'u.education_name',
            'u.education_year',
            'u.employee_id_card_number',
            'u.employee_id_card',
            'u.karisu_number',
            'u.id_tax',
            'u.employment_status',
            'u.id_number',
            'u.family_registration_number',
            'u.residence_id',
            'r.name as residence_name',
            'u.current_address',
            'u.home_phone_number',
            'u.mobile_phone',
            'u.office_address',
            'u.office_phone_number',
            'u.emergency_contact',
            'u.description',
            'u.type',
            'u.created_at'
        );
        $user = $user->first();
        $user->photo_profile = $this->getDocument($user->photo_profile, true);
        if (!is_null($user->position_id)) {
            $user->position_merged = $this->getRecursivePosition($user->position_id);
        }
        return $user;
    }

    /**
     * Get recursive position data
     *
     * @param int $positionId
     * @return void
     */
    private function getRecursivePosition($positionId)
    {
        $sql =
            "WITH RECURSIVE hierarchy AS (
            -- Anchor member: Select the initial child row
            SELECT
                id,
                name,
                parent_id
            FROM
                positions
            WHERE
                id = '$positionId' -- Replace ? with the specific child employee_id

            UNION ALL

            -- Recursive member: Select the parent row
            SELECT
                p.id,
                p.name,
                p.parent_id
            FROM
                positions p
            INNER JOIN
                hierarchy h ON p.id = h.parent_id
            WHERE
                p.entity = 1
        )
        SELECT
            *
        FROM
            hierarchy;";
        $position = DB::select($sql);
        $names = array_column($position, 'name');
        return implode(', ', $names);
    }
}
