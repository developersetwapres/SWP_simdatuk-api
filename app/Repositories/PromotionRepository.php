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
        $groupId = null,
        $echelonId = null,
        $gradeId = null,
        $educationLevel = null,
        $maxAge = null,
        $disciplinaryId = null,
        $targetPredicateId = null,
        $tmtCPNS = null,
        $gradeYear = null,
        $creditScore = null,
        $competencyPoint = null,
    ) {
        $users = DB::table('users as u')
            ->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id')
            ->leftJoin('grades as g', 'u.grade_id', '=', 'g.id')
            ->select(
                "u.id",
                "e.name as echelon_name",
                "u.name",
                "g.name as grade_name",
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
            $users->where('dhu.disciplinary_history_id', '=', $disciplinaryId);
        }

        if (isset($targetPredicateId)) {
            $users->join('target_history_users as thu', 'thu.user_id', '=', 'u.id');
            $users->where('thu.employee_performance_predicate', '=', $targetPredicateId);
        }

        if (isset($tmtCPNS)) {
            $date = strtotime(date('Y-m-d') . ' -' . $tmtCPNS . ' year');
            $year = date('Y', $date);
            $users->where('YEAR(u.tmtcpns)', '=', $year);
        }

        if (isset($gradeYear)) {
            $date = strtotime(date('Y-m-d') . ' -' . $gradeYear . ' year');
            $year = date('Y', $date);
            $users->where('YEAR(u.grade_effective_date)', '=', $year);
        }

        if (isset($creditScore)) {
            $users->join('user_credits as uc', 'uc.user_id', '=', 'u.id');
            $users->where('uc.score', '=', $creditScore);
        }

        if (isset($competencyPoint)) {
            $users->join('user_competencies as uco', 'uco.user_id', '=', 'u.id');
            $users->where('uco.point', '=', $competencyPoint);
        }

        $users->groupBy('u.id');
        return $users->get();
    }
}
