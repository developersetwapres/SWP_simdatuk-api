<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Diagram
 * Below is the endpoint to get top level and child of diagram data
 */
class DiagramController extends Controller
{

    protected $request;
    protected $posted;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Diagrams
     *
     * Below is the list of all data diagrams.
     * @authenticated
     * @bodyParam id integer Refers to the id of parent data. Example: 1
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Staff Khusus Wakil Presiden","type": 1,"available": 0,"filled": 0,"children": 10,"entity": 2,"users": []},{"id": 2,"name": "Kepala Sekretariat Wakil Presiden","type": 1,"available": 1,"filled": 1,"children": 4,"entity": 1,"users": [{"id": 10578,"name": "Ahmad Erani Yustika","echelon_id": null,"echelon_effective_date": null,"grade_id": null,"grade_effective_date": "2022-10-01","employee_id_number": "197303221997021001","employee_registration_number": "197303221997021001"}]},{"id": 3,"name": "Pejabat Kemensetneg yang Diperbantukan di Sekretariat Wakil Presiden","type": 1,"available": 0,"filled": 0,"children": 4,"entity": 2,"users": []}]}
     */
    public function index()
    {
        if (is_null($this->request->id)) {
            return $this->getTopLevelPositions();
        } else {
            return $this->getPositionWithChildren($this->request->id);
        }
    }

    private function getTopLevelPositions()
    {
        $positions = $this->getPositions(null, 2, true);

        foreach ($positions as $position) {
            $position->users = [];
            if ($position->entity == 1) {
                $position->users = $this->getUsers($position->id);
            }
        }

        return $this->response(200, 'success', $positions);
    }

    private function getPositionWithChildren($positionId)
    {
        $positions = $this->getPositions($positionId, 1, false);

        if ($positions) {
            $positions->users = [];
            if ($positions->entity == 1) {
                $positions->users = $this->getUsers($positions->id);
            }

            if ($positions->type == 1) {
                $positions->childs = $this->getPositions($positionId, 2, true);
            } else {
                $positions->childs = $this->getNestedJafung([], $positionId);
            }

            $positions->children = sizeof($positions->childs);

            foreach ($positions->childs as $childPosition) {
                if (isset($childPosition->entity)) {
                    if ($childPosition->entity == 1) {
                        $childPosition->users = $this->getUsers($childPosition->id);
                    } else {
                        $childPosition->users = [];
                    }
                }

                $childPosition->childs = [];
                //jabatan fungsional
                if (isset($childPosition->type) && $childPosition->type == 2) {
                    $childPosition->childs = $this->getNestedJafung([], $childPosition->id);

                    $childPosition->children = sizeof($childPosition->childs);
                    $childPosition->available = 0;
                    $childPosition->filled = 0;
                }

                //special case Pejabat Kemensetneg yang Diperbantukan di Sekretariat Wakil Presiden
                if (isset($positions->id) && $positions->id == 4) {
                    $grandchildPositions = $this->getPositions($childPosition->id, 2, true);
                    foreach ($grandchildPositions as $grandchildPosition) {
                        $grandchildUsers = $this->getUsers($grandchildPosition->id);
                        foreach ($grandchildUsers as $value) {
                            $childPosition->users[] = $value;
                        }
                    }
                }
            }
        }

        return $this->response(200, 'success', $positions);
    }

    private function getNestedJafung($list, $id)
    {
        foreach ($this->getPositionEchelons($id) as $value) {
            $list[] = $value;
        }

        foreach ($this->getPositions($id, 2, true) as $value) {
            if ($value->type == 2) {
                $value->childs = $this->getNestedJafung([], $value->id);
                $value->children = sizeof($value->childs);
            }
            $value->available = 0;
            $value->filled = 0;
            $list[] = $value;
        }

        return $list;
    }

    //idType : 1=id, 2=parent_id
    private function getPositions($id, $idType, $all = true)
    {
        $positions = DB::table('positions')
            ->select(
                'positions.id',
                'positions.name',
                'positions.type',
                DB::raw('CASE WHEN position_echelons.available IS NOT NULL THEN position_echelons.available ELSE positions.available END as available'),
                DB::raw('CASE WHEN position_echelons.filled IS NOT NULL THEN position_echelons.filled ELSE positions.filled END as filled'),
                DB::raw('CASE WHEN position_echelons.children IS NOT NULL THEN position_echelons.children ELSE positions.children END as children'),
                'positions.entity',
            )
            ->leftJoin('position_echelons', 'positions.id', '=', 'position_echelons.position_id')
            ->orderBy('positions.vertical_order')
            ->orderBy('positions.horizontal_order')
            ->orderBy('position_echelons.vertical_order')
            ->orderBy('position_echelons.horizontal_order');

        if ($idType == 1) {
            $positions->where('positions.id', $id);
        } else {
            $positions->where('positions.parent_id', $id);
        }

        if ($all === true) {
            return collect($positions->get()->unique('id')->values());
        } else {
            return $positions->first();
        }
    }

    private function getUsers($positionId, $echelonId = null)
    {
        $users = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.title_prefix',
                'users.title_suffix',
                'users.position_effective_date',
                'users.employee_id_number',
                'users.employee_registration_number',
                'users.type',
                'positions.name as position_name',
                'echelons.name as echelon_name',
                'grades.name as grade_name',
                'grades.code as grade_code',
            )
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            ->leftJoin('echelons', 'echelons.id', '=', 'users.echelon_id')
            ->leftJoin('grades', 'grades.id', '=', 'users.grade_id')
            ->where('users.position_id', $positionId)
            ->orderBy('users.position_effective_date', 'ASC')
            ->orderBy('users.grade_id', 'ASC');

        if (isset($echelonId)) {
            $users->where('users.echelon_id', '=', $echelonId);
        }

        return $users->get();
    }

    private function getPositionEchelons($positionId)
    {
        $positionEchelons = DB::table('position_echelons')
            ->select(
                'echelons.name',
                'position_echelons.available',
                'position_echelons.filled',
                'position_echelons.children',
                'position_echelons.echelon_id',
            )
            ->leftJoin('echelons', 'position_echelons.echelon_id', '=', 'echelons.id')
            ->where('position_id', $positionId)
            ->orderBy('position_echelons.vertical_order')
            ->orderBy('position_echelons.horizontal_order')
            ->get();

        foreach ($positionEchelons as $positionEchelon) {
            $positionEchelon->users = $this->getUsers($positionId, $positionEchelon->echelon_id);
            $positionEchelon->children = sizeof($positionEchelon->users);
        }

        return $positionEchelons;
    }
}
