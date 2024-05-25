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
        $positions = DB::table('positions')
            ->select(
                'id',
                'name',
                'type',
                'available',
                'filled',
                'children',
                'entity'
            )
            ->whereNull('parent_id')
            ->orderBy('vertical_order')
            ->orderBy('horizontal_order')
            ->get();

        foreach ($positions as $position) {
            $position->users = [];
            if ($position->entity == 1) {
                $position->users = $this->getUsersByPositionId($position->id);
            }
        }

        return $this->response(200, 'success', $positions);
    }

    private function getPositionWithChildren($positionId)
    {
        $position = DB::table('positions')
            ->select(
                'id',
                'name',
                'type',
                'entity'
            )
            ->where('id', $positionId)
            ->orderBy('vertical_order')
            ->orderBy('horizontal_order')
            ->first();

        if ($position) {
            $position->users = [];
            if ($position->entity == 1) {
                $position->users = $this->getUsersByPositionId($position->id);
            }

            $position->children = $this->getChildPositions($positionId);

            foreach ($position->children as $childPosition) {
                $childPosition->users = [];
                if ($childPosition->entity == 1) {
                    $childPosition->users = $this->getUsersByPositionId($childPosition->id);
                }
            }
        }

        return $this->response(200, 'success', $position);
    }

    private function getChildPositions($parentId)
    {
        return DB::table('positions')
            ->select(
                'id',
                'name',
                'type',
                'available',
                'filled',
                'children',
                'entity'
            )
            ->where('parent_id', $parentId)
            ->orderBy('vertical_order')
            ->orderBy('horizontal_order')
            ->get();
    }

    private function getUsersByPositionId($positionId)
    {
        return DB::table('users')
            ->select(
                'id',
                'name',
                'echelon_id',
                'echelon_effective_date',
                'grade_id',
                'grade_effective_date',
                'employee_id_number',
                'employee_registration_number'
            )
            ->where('position_id', $positionId)
            ->get();
    }
}
