<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
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
     * @queryParam id integer Refers to the id of parent data. Example: 1
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
        $positions = $this->getPositions(null, 2, true, false, true);

        foreach ($positions as $position) {
            $position->users = [];
            if ($position->entity == 1) {
                $position->users = $this->getUsers($position->id);
                $position->filled = sizeof($position->users);
            }
        }

        return $this->response(200, 'success', $positions);
    }

    private function getPositionWithChildren($positionId)
    {
        $positions = $this->getPositions($positionId, 1, false, false, true);

        if ($positions) {
            $positions->users = [];
            if ($positions->entity == 1) {
                $positions->users = $this->getUsers($positions->id);
                $positions->filled = sizeof($positions->users);
            }

            if ($positions->type == 1) {
                $positions->childs = $this->getPositions($positionId, 2, true, false, true);
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

                $childPosition->filled = sizeof($childPosition->users);

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
                    $grandchildPositions = $this->getPositions($childPosition->id, 2, true, false, true);

                    foreach ($grandchildPositions as $grandchildPosition) {
                        $grandchildUsers = $this->getUsers($grandchildPosition->id);
                        foreach ($grandchildUsers as $value) {
                            $childPosition->users[] = $value;
                        }
                    }

                    $childPosition->filled = sizeof($childPosition->users);
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

        foreach ($this->getPositions($id, 2, true, false, true) as $value) {
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
    private function getPositions($id, $idType, $allData, $withUser, $hasChildrenStatus)
    {
        $positions = DB::table('positions')
            ->select(
                'positions.id',
                'positions.name',
                'positions.type',
                DB::raw('CASE WHEN position_echelons.available IS NOT NULL THEN position_echelons.available ELSE positions.available END as available'),
                'positions.entity',
            )
            ->leftJoin('position_echelons', 'positions.id', '=', 'position_echelons.position_id')
            ->where('positions.type', '!=', 3)
            ->orderBy('positions.vertical_order')
            ->orderBy('positions.horizontal_order')
            ->orderBy('position_echelons.vertical_order')
            ->orderBy('position_echelons.horizontal_order');

        if ($withUser === true) {
            $positions->addSelect(
                'u.name as user_name',
                'u.title_prefix',
                'u.title_suffix',
                'u.photo_profile as user_photo_profile',
                'e.name as echelon_name',
                'g.name as grade_name',
                'u.employee_id_number',
                'u.employee_registration_number',
            );

            $positions->leftJoin('users as u', function ($join) {
                $join->on('positions.id', '=', 'u.position_id');
                $join->on(DB::raw('CASE WHEN position_echelons.echelon_id IS NOT NULL THEN position_echelons.echelon_id ELSE true END'), '=', DB::raw('CASE WHEN position_echelons.echelon_id IS NOT NULL THEN u.echelon_id ELSE true END'));
            });

            $positions->leftJoin('echelons as e', function ($join) {
                $join->on('u.echelon_id', '=', 'e.id');
                $join->on('position_echelons.echelon_id', '=', 'e.id');
            });
            $positions->leftJoin('grades as g', 'u.grade_id', '=', 'g.id');
        }

        if ($idType == 1) {
            $positions->where('positions.id', $id);
        } else {
            $positions->where('positions.parent_id', $id);
        }

        if ($allData === true) {
            if ($withUser != true) {
                $positions = collect($positions->get()->unique('id')->values());
            } else {
                $positions = $positions->get();
            }
        } else {
            $positions = $positions->first();
        }

        if ($hasChildrenStatus === true) {
            if ($allData === true) {
                foreach ($positions as $position) {
                    $hasChild = DB::select('SELECT COUNT(1) as co FROM positions WHERE parent_id = ?', [$position->id]);
                    $position->has_child = $hasChild[0]->co > 0;
                }
            } else {
                $hasChild = DB::select('SELECT COUNT(1) as co FROM positions WHERE parent_id = ?', [$positions->id]);
                $positions->has_child = $hasChild[0]->co > 0;
            }
        }

        return $positions;
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
                'users.photo_profile',
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

        $users = $users->get();

        foreach ($users as $user) {
            $user->photo_profile = $this->getDocument($user->photo_profile, true);
        }

        return $users;
    }

    private function getPositionEchelons($positionId)
    {
        $positionEchelons = DB::table('position_echelons')
            ->select(
                'echelons.name',
                'position_echelons.available',
                'position_echelons.echelon_id',
            )
            ->leftJoin('echelons', 'position_echelons.echelon_id', '=', 'echelons.id')
            ->where('position_id', $positionId)
            ->orderBy('position_echelons.vertical_order')
            ->orderBy('position_echelons.horizontal_order')
            ->get();

        foreach ($positionEchelons as $positionEchelon) {
            $positionEchelon->users = $this->getUsers($positionId, $positionEchelon->echelon_id);
            $positionEchelon->filled = sizeof($positionEchelon->users);
            $positionEchelon->children = 0;
        }

        return $positionEchelons;
    }

    public function export()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);
        $hierarchies = $this->getHierarchy(null, 1);

        // return $hierarchies;

        $html = '<ul><li class="li-last"><a href="#">PARENT</a>';
        $html .= $this->generateHtml($hierarchies);
        $html .= '</li></ul>';

        $tmp = sys_get_temp_dir();

        $pdf = Pdf::loadview('exports/diagram', ['html' => $html]);

        $pdf->set_option('isHtml5ParserEnabled', true);
        // $pdf->set_paper("4a0", "landscape");
        $w = 595.28 * 150;
        $h = 841.89 * 10;
        // a4 = 595.28, 841.89
        $customPaper = array(0, 0, $w, $h);
        $pdf->setPaper($customPaper);
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('diagram.pdf');
    }

    private function getHierarchy($parentId = null, $positionType = null)
    {
        $positions = [];
        if ($positionType == 1) {
            $positions = $this->getPositions($parentId, 2, true, true, false);
        }


        foreach ($positions as $position) {
            $children = $this->getHierarchy($position->id, $position->type);

            if (collect($children)->values()->contains(function ($child) {
                return $child->type == 2;
            })) {
                // $uniqueChildrens = $children->unique('id')->values();

                // foreach ($uniqueChildrens as $uniqueChildren) {
                //     $grandChild = $this->getHierarchy($uniqueChildren->id, $uniqueChildren->type);

                //     $uniqueChildren->children = $grandChild;
                // }
            }

            $position->children = $children;
        }

        return $positions;
    }

    private function generateHtml($hierarchies)
    {
        $html = '<ul>';
        foreach ($hierarchies as $key => $hierarchy) {
            if (sizeof($hierarchies) == 1) {
                $html .= '<li class="li-single">';
            } else if ($key == 0 && sizeof($hierarchies) > 1) {
                $html .= '<li class="li-last">';
            } else {
                $html .= '<li class="li-left li-last">';
            }

            if (($hierarchy->type == 1 && $hierarchy->entity == 1) || $hierarchy->type == 2) {
                //card person

                $userName = '-';

                if (isset($hierarchy->user_name)) {
                    $userName = $hierarchy->user_name;
                }
                if (isset($hierarchy->title_prefix)) {
                    $userName = $hierarchy->title_prefix . ' ' . $userName;
                }
                if (isset($hierarchy->title_suffix)) {
                    $userName = $userName . ' ' . $hierarchy->title_suffix;
                }

                $html .= '
                        <div class="node-person">
                            <table style="width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="node-position-name-person">
                                        ' . ($hierarchy->name ?? '-') . '
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-photo-container">
                                        <img src="' . (isset($hierarchy->user_photo_profile) ? $this->getDocument($hierarchy->user_photo_profile, true) : 'img/profile.jpg') . '" class="node-photo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-user-name">
                                        ' . $userName . '
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-title">
                                        Eselon
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-value">
                                        ' . ($hierarchy->echelon_name ?? '-') . '
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-title">
                                        Golongan
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-value">
                                        ' . ($hierarchy->grade_name ?? '-') . '
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TMT
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-title">
                                        NIP/NRP
                                    </td>
                                </tr>
                                <tr>
                                    <td class="node-item-value">
                                        ' . ($hierarchy->employee_id_number ?? '-') . '/' . ($hierarchy->employee_registration_number ?? '-') . '
                                    </td>
                                </tr>
                            </table>
                        </div>
                        ';
            } else {
                //card non person
                $html .= '
                        <div class="node-non-person">
                            <table style="width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="node-position-name-non-person">
                                        ' . ($hierarchy->name ?? '-') . '
                                    </td>
                                </tr>
                            </table>
                        </div>
                        ';
            }
            if (sizeof($hierarchy->children)) {
                $html .= $this->generateHtml($hierarchy->children);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}
