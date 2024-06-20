<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\CreatePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Repositories\PositionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @group Master Data
 * These endpoints would allow you to track and manage the master data of position, grade, institution, employment type, decree type, and other pertinent events.
 * @subgroupDescription These endpoints allow you to perform CRUD operations on position data, enabling the retrieval, creation, updating, and deleting of position records as needed.
 */
class PositionController extends Controller
{
    protected $positionRepository;

    protected $request;
    protected $posted;

    public function __construct(
        Request $request,
        PositionRepository $positionRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->positionRepository = $positionRepository;
    }

    /**
     * Get List of Positions
     *
     * Retrieve the position of master data.
     * @subgroup Position
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name or code. Example: pembina utama
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Pembina Utama","code": "IV/e","type": "PNS"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/grades?page=1","last_page": "http://localhost/api/grades?page=32","next_page": "http://localhost/api/grades?page=2","prev_page": null}}}
     *
     */
    public function index()
    {
        try {
            $messages = [
                'page.numeric' => 'Page harus berupa angka.',
                'page.min' => 'Page minimal harus 1 atau lebih.',
                'limit.numeric' => 'Limit harus berupa angka.',
                'limit.min' => 'Limit minimal harus 1 atau lebih.',
            ];

            $this->request->validate([
                'page' => 'nullable|numeric|min:1',
                'limit' => 'nullable|numeric|min:1',
            ], $messages);

            $positions = DB::table('positions')
                ->select(
                    'positions.id',
                    'positions.name',
                    'positions.type',
                    'positions.parent_id',
                )
                ->orderBy('positions.id', 'ASC');

            if (!is_null($this->request->keyword)) {
                $positions->where('positions.name', 'LIKE', '%' . $this->request->keyword . '%');
            }

            $positions = $positions->get();

            foreach ($positions as $position) {
                $position->type = [
                    "id" => $position->type,
                    "name" => $position->type == 1 ? 'Struktural' : ($position->type == 2 ? 'Fungsional' : 'Outsource')
                ];

                $parentId = $position->parent_id;
                $shownHierarcy = '';

                //get last 3 parent
                $last3Parent = $this->positionRepository->getRecursivePosition($position->id, 4);
                $last3Parent = collect($last3Parent)->filter(function ($item) use ($position) {
                    return $item->id != $position->id;
                })->reverse()->values()->all();

                if (sizeof($last3Parent)) {
                    foreach ($last3Parent as $key => $value) {
                        if ($key > 0) {
                            $shownHierarcy .= " > ";
                        }
                        $shownHierarcy .= $value->name;
                    }
                } else {
                    $shownHierarcy = '-';
                }

                $position->hierarchies = $shownHierarcy;

                //remove unecessary select
                unset($position->parent_id);
            }

            if (is_null($this->request->limit)) {
                $message = (count($positions) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
                return $this->response(200, $message, $positions);
            } else {
                $page = $this->request->get('page', 1);

                $paginatedUsers = new LengthAwarePaginator(
                    $positions->forPage($page, $this->request->limit),
                    $positions->count(),
                    $this->request->limit,
                    $page,
                    ['path' => $this->request->url(), 'query' => $this->request->query()]
                );

                $message = ($paginatedUsers->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
                return $this->paginateResponse(200, $message, $paginatedUsers);
            }
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    public function create(CreatePositionRequest $request)
    {
        try {
            DB::beginTransaction();

            //modify request
            if (sizeof($this->request->position_echelons)) {
                $this->request->merge(['available' => 0]);
            }

            if (is_null($this->request->parent_id)) {
                $this->request->merge(['vertical_order' => $this->request->order]);
                $this->request->request->remove('order');
            } else {
                $this->request->merge(['horizontal_order' => $this->request->order]);
                $this->request->request->remove('order');
            }

            $positionId = DB::table('positions')->insertGetIdTs($this->request->except('position_echelons'));

            //insert position_echelons if any
            if (sizeof($this->request->position_echelons)) {
                $positionEchelons = array();
                foreach ($this->request->position_echelons as $key => $positionEchelon) {
                    $positionEchelon['position_id'] = $positionId;
                    $positionEchelon['horizontal_order'] = $key + 1;
                    array_push($positionEchelons, $positionEchelon);
                }
                DB::table('position_echelons')->insertTs($positionEchelons);
            }

            DB::commit();
            return $this->response(200, 'Jabatan berhasil ditambah.');
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    public function show()
    {
        try {
            $position = DB::table('positions')
                ->select(
                    'id',
                    'name',
                    'available',
                    'type',
                    'entity',
                    'vertical_order',
                    'horizontal_order',
                    'parent_id'
                )
                ->where('id', $this->request->id);
            $position = $position->first();

            if (!$position) {
                return $this->response(404, 'Jabatan tidak ditemukan.');
            }

            $position->filled = DB::table('users')->where('position_id', $position->id)->count();

            $position->type = [
                "id" => $position->type,
                "name" => $position->type == 1 ? 'Struktural' : ($position->type == 2 ? 'Fungsional' : 'Outsource')
            ];

            $position->entity = [
                "id" => $position->entity,
                "name" => $position->entity == 1 ? 'Orang' : 'Kelompok'
            ];

            if (isset($position->parent_id)) {
                $position->order = $position->horizontal_order;
            } else {
                $position->order = $position->vertical_order;
            }

            //get echelons
            $positionEchelons = DB::table('position_echelons')
                ->select(
                    'position_echelons.id',
                    'echelons.name',
                    'position_echelons.available',
                    'position_echelons.position_id'
                )
                ->join('echelons', 'position_echelons.echelon_id', '=', 'echelons.id')
                ->where('position_echelons.position_id', $position->id)
                ->get();

            foreach ($positionEchelons as $positionEchelon) {
                $users = DB::select('SELECT COUNT(1) as count FROM users WHERE echelon_id = ? AND position_id = ?', [$positionEchelon->id, $positionEchelon->position_id]);
                $positionEchelon->filled = $users[0]->count;
                unset($positionEchelon->position_id);
            }

            $position->echelons = $positionEchelons;

            $hierarchies = $this->positionRepository->getRecursivePosition($position->id);
            $hierarchies = collect($hierarchies)->filter(function ($item) use ($position) {
                return $item->id != $position->id;
            })->reverse()->values()->all();

            $position->hierarchies = $hierarchies;

            unset($position->parent_id);
            unset($position->horizontal_order);
            unset($position->vertical_order);

            return $this->response(200, 'success', $position);
        } catch (\Throwable $th) {
            Log::warning($th);
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    public function update(UpdatePositionRequest $request)
    {
        try {
            DB::beginTransaction();
            $position = DB::table('positions')
                ->select(
                    'id'
                )
                ->where('id', $this->request->id);
            $position = $position->first();

            if (!$position) {
                return $this->response(404, 'Jabatan tidak ditemukan.');
            }

            //modify request
            if (sizeof($this->request->position_echelons)) {
                $this->request->merge(['available' => 0]);
            }

            if (is_null($this->request->parent_id)) {
                $this->request->merge(['vertical_order' => $this->request->order]);
                $this->request->request->remove('order');
            } else {
                $this->request->merge(['horizontal_order' => $this->request->order]);
                $this->request->request->remove('order');
            }

            DB::table('positions')
                ->where('id', $this->request->id)
                ->updateTs($this->request->except('position_echelons', 'deleted_echelon_id'));

            if (isset($this->request->deleted_echelon_id)) {
                DB::table('position_echelons')
                    ->whereIn('id', $this->request->deleted_echelon_id)
                    ->where('position_id', $position->id)
                    ->delete();
            }

            $positionEchelonsInsert = array();
            foreach ($this->request->position_echelons as $key => $value) {
                if (isset($value['id'])) {
                    $value['horizontal_order'] = $key + 1;
                    DB::table('position_echelons')
                        ->where('id', $value['id'])
                        ->updateTs($value);
                } else {
                    $value['position_id'] = $position->id;
                    $value['horizontal_order'] = $key + 1;
                    array_push($positionEchelonsInsert, $value);
                }
            }

            if (sizeof($positionEchelonsInsert)) {
                DB::table('position_echelons')->insertTs($positionEchelonsInsert);
            }

            DB::commit();
            return $this->response(200, 'Jabatan berhasil diubah.');
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    public function delete()
    {
        try {
            $position = DB::table('positions')
                ->select('id')
                ->where('id', $this->request->id);
            $position = $position->first();

            if (!$position) {
                return $this->response(404, 'Jabatan tidak ditemukan.');
            }

            $existUser = DB::table('users')
                ->where('position_id', $position->id)
                ->count();

            if ($existUser > 0) {
                return $this->response(404, 'Jabatan ini masih digunakan oleh beberapa pegawai.');
            }

            DB::table('positions')
                ->where('id', $position->id)
                ->delete();

            return $this->response(200, 'Jabatan berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::warning($th);
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }
}
