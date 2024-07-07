<?php

namespace App\Repositories;

use App\Helpers\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PromotionRepository
{
    use Document;

    // type 1=structural, 2=functional
    public function getAvailablePosition($type, $echelonId = [], $positionNames = [])
    {
        if ($type == 1 && sizeof($echelonId)) {
            $availableSQL = "";
            $filledSQL = "";

            foreach ($echelonId as $key => $value) {
                $availableSQL .= "WHEN e.id = " . $value . " THEN COALESCE(SUM(CASE WHEN p.type = 1 AND pe.echelon_id = e.id THEN COALESCE(pe.available, 0) END), 0) - su.filled_eselon" . $value . " ";
                $filledSQL .= "COALESCE(COUNT(CASE WHEN p.type = 1 AND pe.echelon_id = " . $value . " AND u.position_id = p.id THEN 1 END), 0) as filled_eselon" . $value;
                if ($key < sizeof($echelonId) - 1) {
                    $filledSQL .= ",";
                }
                $filledSQL .= " ";
            }
            $query = "
                SELECT
                    e.id as echelon_id,
                    e.name as echelon_name,
                    CASE " . $availableSQL . " END as unoccupied,
                    1 as type
                FROM
                echelons e
                LEFT JOIN position_echelons pe ON e.id = pe.echelon_id
                LEFT JOIN positions p ON p.id = pe.position_id
                JOIN (
                    SELECT
                        pe.echelon_id, " . $filledSQL . "
                    FROM positions p
                    JOIN position_echelons pe ON p.id = pe.position_id
                    JOIN users u ON u.position_id = pe.position_id
                    GROUP BY pe.echelon_id) su ON su.echelon_id = pe.echelon_id
                WHERE e.id IN (" . implode(", ", $echelonId) . ")
                GROUP BY e.id
                ORDER BY e.id ASC
            ";

            return DB::select($query);
        } else if ($type == 2 && sizeof($positionNames)) {
            $whereName = " WHERE ";
            foreach ($positionNames as $key => $positionName) {
                if ($key > 0) {
                    $whereName .= "OR ";
                }
                $whereName .= "p.name LIKE '%" . $positionName . "%' ";
            }

            $query = "
                SELECT
                    e.id as echelon_id,
                    e.NAME as echelon_name,
                    p.NAME as position_name,
                    SUM(pe.available) - su.occupied as unoccupied,
                    2 as type
                FROM
                    echelons e
                    JOIN position_echelons pe ON pe.echelon_id = e.id
                    JOIN positions p ON pe.position_id = p.id AND p.type = 2
                    LEFT JOIN (
                        SELECT
                                e.id,
                                p.name,
                                COUNT(u.id) as occupied
                        FROM echelons e
                        LEFT JOIN position_echelons pe ON e.id = pe.echelon_id
                        LEFT JOIN positions p ON p.id = pe.position_id AND p.type = 2
                        LEFT JOIN users u ON u.position_id = p.id AND u.echelon_id = e.id
                        GROUP BY e.id, p.name) su ON su.id = e.id AND su.`name` = p.`name`
                " . $whereName . "
                GROUP BY e.id, p.name
                ORDER BY e.id ASC
            ";

            return DB::select($query);
        }
    }

    public function getPositionIdByName($name)
    {
        $positions = DB::table('positions')
            ->where('name', $name)
            ->get();

        $positions = Arr::pluck($positions, 'id');
        return implode(",", $positions);
    }

    public function getPromotionByEchelonId($echelonId, $positionIds = [])
    {
        $params[] = $echelonId;
        $wherePosition = '';
        if (sizeof($positionIds)) {
            $wherePosition = " AND pe.position_id IN (" . implode(",", array_fill(0, count($positionIds), '?')) . ")";
            $params = array_merge($params, $positionIds);
        }

        $sql = "
            SELECT
                pe.id,
                pe.position_id,
                e.name,
                pe.available - COALESCE ( su.unoccupied, 0 ) as unoccupied
            FROM
                position_echelons pe
                JOIN echelons e ON pe.echelon_id = e.id
                LEFT JOIN (
                SELECT
                    pe.position_id,
                    pe.echelon_id,
                    COUNT( 1 ) AS unoccupied
                FROM
                    position_echelons pe
                    JOIN users u ON pe.echelon_id = u.echelon_id
                    AND pe.position_id = u.position_id
                    JOIN positions p ON pe.position_id = p.id
                GROUP BY
                    pe.echelon_id,
                    pe.position_id
                ) su ON su.position_id = pe.position_id
                AND su.echelon_id = pe.echelon_id
            WHERE
                pe.echelon_id = ?" . $wherePosition;

        return DB::select($sql, $params);
    }

    public function getUserByFilter(
        $page = null,
        $limit = null,
        $groupId = null,
        $echelonId = null,
        $gradeId = null,
        $educationLevel = null,
        $maxAge = null,
        $disciplinaryId = null,
        $targetPredicateId = null,
        $cpnsYear = null,
        $gradeYear = null,
        $creditScore = null,
        $competencyPoint = null,
    ) {
        $users = DB::table('users as u')
            ->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id')
            ->leftJoin('grades as g', 'u.grade_id', '=', 'g.id')
            ->leftJoin('positions as p', 'u.position_id', '=', 'p.id')
            ->select(
                "u.id",
                "u.name",
                "u.photo_profile",
                "u.title_prefix",
                "u.title_suffix",
                "e.name as echelon_name",
                "u.echelon_effective_date",
                "g.name as grade_name",
                "u.grade_effective_date",
                "p.name as position_name",
                "u.employee_id_number",
                "u.employee_registration_number",
            );

        if (isset($groupId)) {
            $users->join('position_history_users as phu', 'phu.user_id', '=', 'u.id');
            $users->where('phu.group_id', '=', $groupId);
        }

        if (isset($echelonId)) {
            $users->where('u.echelon_id', '=', $echelonId);
        }

        if (isset($gradeId)) {
            $users->where('u.grade_id', '=', $gradeId);
        }

        if (isset($educationLevel)) {
            $users->where('u.education_level', '=', $educationLevel);
        }

        if (isset($maxAge)) {
            $date = strtotime(date('Y-m-d') . ' -' . $maxAge . ' year');
            $users->where('u.date_of_birth', '>=', date('Y-m-d', $date));
        }

        if (isset($disciplinaryId)) {
            $users->join('disciplinary_history_users as dhu', 'dhu.user_id', '=', 'u.id');
            $users->where('dhu.disciplinary_id', '=', $disciplinaryId);
        }

        if (isset($targetPredicateId)) {
            $users->join('target_history_users as thu', 'thu.user_id', '=', 'u.id');
            $users->where('thu.employee_performance_predicate', '=', $targetPredicateId);
        }

        if (isset($cpnsYear)) {
            $date = strtotime(date('Y-m-d') . ' -' . $cpnsYear . ' year');
            $users->where('u.cpns_effective_date', '<=', date('Y-m-d', $date));
        }

        if (isset($gradeYear)) {
            $date = strtotime(date('Y-m-d') . ' -' . $gradeYear . ' year');
            $users->where('u.grade_effective_date', '<=', date('Y-m-d', $date));
        }

        if (isset($creditScore)) {
            $users->join('user_credits as uc', 'uc.user_id', '=', 'u.id');
            $users->where('uc.score', '=', $creditScore);
        }

        if (isset($competencyPoint)) {
            $users->join('user_competencies as uco', 'uco.user_id', '=', 'u.id');
            $users->where('uco.point', '=', $competencyPoint);
        }

        $users->whereIn('u.employment_status', [1, 6, 7, 8]);
        $users->where('u.type', 1);
        $users->groupBy('u.id');
        $users->orderBy('u.employment_status', 'asc');
        $users->orderBy('u.echelon_id', 'asc');

        if (isset($limit)) {
            $users = $users->paginate($limit);
        } else {
            $users = $users->get();
        }

        foreach ($users as $user) {
            $user->photo_profile = $this->getDocument($user->photo_profile, true);
        }

        return $users;
    }

    public function getUserByIds($userIds = [])
    {
        $users = DB::table('users as u')
            ->select(
                'u.id as user_id',
                "u.photo_profile",
                'u.name as user_name',
                "u.title_prefix",
                "u.title_suffix",
                "u.employee_id_number",
                "u.employee_registration_number",
                "e.id as echelon_id",
                "e.name as echelon_name",
                "g.id as grade_id",
                "g.name as grade_name",
                "g.type as grade_type",
                "u.grade_effective_date",
                "u.cpns_effective_date",
                "u.education_level",
            )
            ->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id')
            ->leftJoin('grades as g', 'u.grade_id', '=', 'g.id')
            ->whereIn('u.id', $userIds)
            ->get();

        //ECHELON SCORES
        $echelonIds = $users->pluck('echelon_id')->toArray();

        // define custom ranks based on the rules
        usort($echelonIds, function ($a, $b) {
            $rank = function ($value) {
                if ($value >= 1 && $value <= 4) {
                    return 1;
                } elseif ($value == 9) {
                    return 2;
                } elseif ($value >= 5 && $value <= 8) {
                    return 3;
                } else {
                    return 4;
                }
            };

            return $rank($a) <=> $rank($b);
        });
        $uniqueEchelon = collect($echelonIds)
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();
        $echelonCount = sizeof($uniqueEchelon);

        foreach ($users as $user) {
            if ($user->echelon_id) {
                $rank = array_search($user->echelon_id, $uniqueEchelon, true);
                $user->echelon_percentage = (int) (100 - ($rank * (100 / $echelonCount)));
            } else {
                $user->echelon_percentage = 0;
            }
        }
        //END OF ECHELON SCORES

        //GRADES SCORES
        $type1Grades = $users->filter(function ($item) {
            return $item->grade_type == 1;
        })->values();
        $type1Grades = $type1Grades->sortBy('grade_id')->pluck('grade_id')->unique()->values()->toArray();

        $type2Grades = $users->filter(function ($item) {
            return $item->grade_type == 2;
        })->values();
        $type2Grades = $type2Grades->sortBy('grade_id')->pluck('grade_id')->unique()->values()->toArray();

        foreach ($users as $user) {
            if ($user->grade_id) {
                if ($user->grade_type == 1) {
                    $rank = array_search($user->grade_id, $type1Grades, true);
                    $user->grade_percentage = (int) (100 - ($rank * (100 / sizeof($type1Grades))));
                } else if ($user->grade_type == 2) {
                    $rank = array_search($user->grade_id, $type2Grades, true);
                    $user->grade_percentage = (int) (100 - ($rank * (100 / sizeof($type2Grades))));
                }
            } else {
                $user->grade_percentage = 0;
            }
        }
        //END OF GRADES SCORES

        //GRADE EFFECTIVE DATE SCORES
        $gradeEffectiveDates = $users
            ->sortBy('grade_effective_date')
            ->pluck('grade_effective_date')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            if ($user->grade_effective_date) {
                $rank = array_search($user->grade_effective_date, $gradeEffectiveDates, true);
                $user->grade_effective_date_percentage = (int) (100 - ($rank * (100 / sizeof($gradeEffectiveDates))));
            } else {
                $user->grade_effective_date_percentage = 0;
            }
        }
        //END OF GRADE EFFECTIVE DATE SCORES

        //CPNS EFFECTIVE DATE SCORES
        $CPNSEffectiveDates = $users
            ->sortBy('cpns_effective_date')
            ->pluck('cpns_effective_date')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            if ($user->cpns_effective_date) {
                $rank = array_search($user->cpns_effective_date, $CPNSEffectiveDates, true);
                $user->cpns_effective_date_percentage = (int) (100 - ($rank * (100 / sizeof($CPNSEffectiveDates))));
            } else {
                $user->cpns_effective_date_percentage = 0;
            }
        }
        //END OF CPNS EFFECTIVE DATE SCORES

        //EDUCATION LEVEL SCORES
        $educationLevels = $users
            ->sortByDesc('education_level')
            ->pluck('education_level')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            if ($user->education_level) {
                $rank = array_search($user->education_level, $educationLevels, true);
                $user->education_level_percentage = (int) (100 - ($rank * (100 / sizeof($educationLevels))));
            } else {
                $user->education_level_percentage = 0;
            }
        }
        //END OF EDUCATION LEVEL SCORES

        //USER NOTES
        foreach ($users as $user) {
            $user->notes = $this->getUserNotes($user->user_id);
        }
        //END OF USER NOTES

        //ASSESSMENT
        $userIds = $users->pluck('user_id')->toArray();

        $assessmentParams = [];
        $assessmentParams[] = now()->year;
        $assessmentParams = array_merge($assessmentParams, $userIds);

        $assessments = DB::select('
            SELECT
                user_id,
                point
            FROM
                user_assessments
            WHERE
                id IN (SELECT
                        MAX(id)
                        FROM user_assessments
                        WHERE
                        YEAR(event_date) = ?
                        AND user_id IN(' . implode(",", array_fill(0, count($userIds), '?')) . ')
                        GROUP BY user_id)
            ORDER BY
                point DESC', $assessmentParams);

        $assessmentPoints = collect($assessments)
            ->pluck('point')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            $userAssessment = collect($assessments)->filter(function ($data) use ($user) {
                return $data->user_id == $user->user_id;
            })->values();

            if (sizeof($userAssessment)) {
                $user->assessment_point = $userAssessment[0]->point;
                $rank = array_search($userAssessment[0]->point, $assessmentPoints, true);
                $user->assessment_point_percentage = (int) (100 - ($rank * (100 / sizeof($assessmentPoints))));

                switch ($user->assessment_point) {
                    case 1:
                        $user->assessment_point_name = 'Kurang Memenuhi Syarat';
                        break;
                    case 2:
                        $user->assessment_point_name = 'Masih Memenuhi Syarat';
                        break;
                    case 3:
                        $user->assessment_point_name = 'Memenuhi Syarat';
                        break;
                }
            } else {
                $user->assessment_point = '-';
                $user->assessment_point_percentage = 0;
                $user->assessment_point_name = '-';
            }
        }
        //END OF ASSESSMENT

        //COMPETENCY
        $competencyParams = [];
        $competencyParams[] = now()->year;
        $competencyParams = array_merge($competencyParams, $userIds);

        $competencies = DB::select('
            SELECT
                user_id,
                point
            FROM
                user_competencies
            WHERE
                id IN (SELECT
                        MAX(id)
                        FROM user_competencies
                        WHERE
                        YEAR(event_date) = ?
                        AND user_id IN(' . implode(",", array_fill(0, count($userIds), '?')) . ')
                        GROUP BY user_id)
            ORDER BY
                point ASC', $competencyParams);

        $competencyPoints = collect($competencies)
            ->pluck('point')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            $userCompetency = collect($competencies)->filter(function ($data) use ($user) {
                return $data->user_id == $user->user_id;
            })->values();

            if (sizeof($userCompetency)) {
                $user->competency_point = $userCompetency[0]->point;
                $rank = array_search($userCompetency[0]->point, $competencyPoints, true);
                $user->competency_point_percentage = (int) (100 - ($rank * (100 / sizeof($competencyPoints))));

                switch ($user->competency_point) {
                    case 1:
                        $user->competency_point_name = 'Lulus';
                        break;
                    case 2:
                        $user->competency_point_name = 'Tidak Lulus';
                        break;
                }
            } else {
                $user->competency_point = '-';
                $user->competency_point_percentage = 0;
                $user->competency_point_name = '-';
            }
        }
        //END OF COMPETENCY

        //TALENT POOL
        $talentParams = [];
        $talentParams[] = now()->year;
        $talentParams = array_merge($talentParams, $userIds);

        $talents = DB::select('
            SELECT
                user_id,
                point
            FROM
                user_talents
            WHERE
                id IN (SELECT
                        MAX(id)
                        FROM user_talents
                        WHERE
                        YEAR(event_date) = ?
                        AND user_id IN(' . implode(",", array_fill(0, count($userIds), '?')) . ')
                        GROUP BY user_id)
            ORDER BY
                point DESC', $talentParams);

        $talentPoints = collect($talents)
            ->pluck('point')
            ->unique()
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->values()
            ->toArray();

        foreach ($users as $user) {
            $userTalent = collect($talents)->filter(function ($data) use ($user) {
                return $data->user_id == $user->user_id;
            })->values();

            if (sizeof($userTalent)) {
                $user->talent_point = $userTalent[0]->point;
                $rank = array_search($userTalent[0]->point, $talentPoints, true);
                $user->talent_point_percentage = (int) (100 - ($rank * (100 / sizeof($talentPoints))));
                $user->talent_point_name = 'Kotak ' . $userTalent[0]->point;
            } else {
                $user->talent_point = '-';
                $user->talent_point_percentage = 0;
                $user->talent_point_name = '-';
            }
        }
        //END OF TALENT POOL

        $returnedData = [];
        foreach ($users as $user) {
            $educationName = '';

            switch ($user->education_level) {
                case 1:
                    $educationName = 'SD/Sederajat';
                    break;
                case 2:
                    $educationName = 'SLTP/Sederajat';
                    break;
                case 3:
                    $educationName = 'SLTA/Sederajat';
                    break;
                case 4:
                    $educationName = 'Diploma I/II';
                    break;
                case 5:
                    $educationName = 'Akademik/D3/S.Muda';
                    break;
                case 6:
                    $educationName = 'Diploma IV/Strata I';
                    break;
                case 7:
                    $educationName = 'Strata II';
                    break;
                case 8:
                    $educationName = 'Strata III';
                    break;
            }

            $returnedData[] = (object) [
                'id' => $user->user_id,
                'name' => $user->user_name,
                'title_prefix' => $user->title_prefix,
                'title_suffix' => $user->title_suffix,
                'photo_profile' => $this->getDocument($user->photo_profile, true),
                'employee_id_number' => $user->employee_id_number,
                'employee_registration_number' => $user->employee_registration_number,
                'echelon' => (object) [
                    'id' => $user->echelon_id,
                    'name' => $user->echelon_name,
                    'percentage' => $user->echelon_percentage,
                ],
                'grade' => (object) [
                    'id' => $user->grade_id,
                    'name' => $user->grade_name,
                    'percentage' => $user->grade_percentage,
                ],
                'grade_effective_date' => (object) [
                    'name' => $user->grade_effective_date,
                    'percentage' => $user->grade_effective_date_percentage,
                ],
                'cpns_effective_date' => (object) [
                    'name' => $user->cpns_effective_date,
                    'percentage' => $user->cpns_effective_date_percentage,
                ],
                'education_level' => (object) [
                    'id' => $user->education_level,
                    'name' => $educationName,
                    'percentage' => $user->education_level_percentage,
                ],
                'assessment' => (object) [
                    'point' => $user->assessment_point,
                    'name' => $user->assessment_point_name,
                    'percentage' => $user->assessment_point_percentage,
                ],
                'competency' => (object) [
                    'point' => $user->competency_point,
                    'name' => $user->competency_point_name,
                    'percentage' => $user->competency_point_percentage,
                ],
                'talent' => (object) [
                    'point' => $user->talent_point,
                    'name' => $user->talent_point_name,
                    'percentage' => $user->talent_point_percentage,
                ],
                'notes' => (object) $user->notes,
            ];
        }

        return $returnedData;
    }

    public function getUserNotes($userId)
    {
        $notes = DB::table('user_notes as un')
            ->leftJoin('users as u', 'un.giver_id', '=', 'u.id')
            ->where('un.user_id', $userId)
            ->select(
                'un.id',
                'un.description',
                'u.name as giver_name',
                'un.created_at',
            )
            ->orderBy('un.created_at', 'desc');
        return $notes = $notes->get();
    }
}
