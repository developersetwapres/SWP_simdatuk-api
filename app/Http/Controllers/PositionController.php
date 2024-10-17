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
     * Retrieve list of the position on master data.
     * @subgroup Position
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The search search field for the name or code. Example: kepala sekretariat
     * @queryParam filter_parent boolean flagging to filter based on parent_id. Example: true
     * @queryParam parent_id integer filter position based on parent_id, filter_parent must be set to true. Example: 1
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Pembina Utama","code": "IV/e","type": "PNS"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/grades?page=1","last_page": "http://localhost/api/grades?page=32","next_page": "http://localhost/api/grades?page=2","prev_page": null}}}
     *
     */
    public function index()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
            'type.regex' => 'Format type tidak sesuai.',
        ];

        $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
            'type' => 'nullable|regex:/^\d+(,\d+)*$/',
        ], $messages);

        try {
            $positions = DB::table('positions')
                ->select(
                    'positions.id',
                    'positions.name',
                    'positions.type',
                    'positions.parent_id',
                )
                ->orderBy('positions.id', 'ASC');

            if (isset($this->request->type)) {
                $positions->whereIn('positions.type', explode(',', $this->request->type));
            }

            if (!is_null($this->request->search)) {
                $positions->where('positions.name', 'LIKE', '%' . $this->request->search . '%');
            }

            if (($this->request->filter_parent === true || $this->request->filter_parent === 'true')) {
                $positions->where('parent_id', $this->request->parent_id);
            }

            $positions = $positions->get();

            $dupePositions = [];
            $originalPositions = unserialize(serialize($positions));

            foreach ($positions as $position) {
                $position->type = [
                    "id" => $position->type,
                    "name" => $position->type == 1 ? 'Struktural' : ($position->type == 2 ? 'Fungsional' : 'Outsource'),
                ];

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

                //rename with name
                $countDupe = collect($originalPositions)->filter(function ($item) use ($position) {
                    return $item->name == $position->name;
                })->values()->all();

                if (sizeof($countDupe) > 1) {
                    $user = DB::table('users')
                        ->select('name')
                        ->where('position_id', $position->id)
                        ->first();
                    if ($user) {
                        $position->name = $position->name . " (" . $user->name . ")";
                    } else {
                        $dupePositions[] = $position->name;
                        $position->name = $position->name . " " . sizeof($dupePositions);
                    }
                }

                $uniquePositions[] = $position->name;

                //remove unecessary select
                unset($position->parent_id);
            }

            if (is_null($this->request->limit)) {
                $message = (count($positions) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
                return $this->response(200, $message, $positions);
            } else {
                $page = $this->request->get('page', 1);

                $paginatedUsers = new LengthAwarePaginator(
                    $positions->forPage($page, $this->request->limit)->values(),
                    $positions->count(),
                    $this->request->limit,
                    $page,
                    ['path' => $this->request->url(), 'query' => $this->request->query()]
                );

                // $paginatedUsers = $paginatedUsers->values();

                $message = ($paginatedUsers->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
                return $this->paginateResponse(200, $message, $paginatedUsers);
            }
        } catch (\Throwable $th) {
            Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Create a New Position
     *
     * Add a new position entry.
     * @subgroup Position
     * @authenticated
     * @response 200 {"code": 200,"message": "Jabatan berhasil ditambah.","data": null}
     */
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
                $this->request->merge(['horizontal_order' => 1]);
                $this->request->request->remove('order');
            } else {
                $this->request->merge(['vertical_order' => 1]);
                $this->request->merge(['horizontal_order' => $this->request->order]);
                $this->request->request->remove('order');
            }

            $positionId = DB::table('positions')
                ->insertGetIdTs($this->request->except('position_echelons'));

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

    /**
     * Get Detail Positions by ID
     *
     * Retrieve the detail position for specific ID.
     * @subgroup Position
     * @authenticated
     * @urlParam id integer Refers to the id of position. Example: 1
     * @response 200 {"code":200,"message":"success","data":{"id":142,"name":"Analis Pengelola Keuangan APBN","available":0,"type":{"id":2,"name":"Fungsional"},"entity":{"id":2,"name":"Kelompok"},"filled":16,"order":1,"echelons":[{"id":102,"name":"Ahli Madya","available":5,"filled":0},{"id":103,"name":"Ahli Muda","available":9,"filled":0},{"id":104,"name":"Ahli Pertama","available":6,"filled":0}],"hierarchies":[{"id":2,"name":"Kepala Sekretariat Wakil Presiden","parent_id":null},{"id":40,"name":"Deputi Bidang Administrasi","parent_id":2},{"id":99,"name":"Kepala Biro Perencanaan dan Keuangan","parent_id":40}]}}
     *
     */
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
                    'parent_id',
                    'status'
                )
                ->where('id', $this->request->id)
                ->first();

            if (!$position) {
                return $this->response(404, 'Jabatan tidak ditemukan.');
            }

            // Exclude employment_type_id = 16 (TNP2K) when count
            $position->filled = DB::table('users')->where('position_id', $position->id)->whereIn('employment_status', [1, 6])->whereNot('employment_type_id', 16)->count();

            $position->type = [
                "id" => $position->type,
                "name" => $position->type == 1 ? 'Struktural' : ($position->type == 2 ? 'Fungsional' : 'Outsource'),
            ];

            $position->entity = [
                "id" => $position->entity,
                "name" => $position->entity == 1 ? 'Orang' : 'Kelompok',
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
                    'echelons.id as echelon_id',
                    'echelons.name',
                    'position_echelons.available',
                    'position_echelons.position_id'
                )
                ->join('echelons', 'position_echelons.echelon_id', '=', 'echelons.id')
                ->where('position_echelons.position_id', $position->id)
                ->get();

            foreach ($positionEchelons as $positionEchelon) {
                // Exclude employment_type_id = 16 (TNP2K) when count
                $users = DB::select('SELECT COUNT(1) as count FROM users WHERE echelon_id = ? AND position_id = ? AND employment_status IN (1, 6) AND employment_type_id != 16', [$positionEchelon->echelon_id, $positionEchelon->position_id]);
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

    /**
     * Update Position by ID
     *
     * Update an existing position entry.
     * @subgroup Position
     * @authenticated
     * @urlParam id Refers to the ID of Position History. Example: 1
     * @response 404 {"code": 404,"message": "Jabatan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Jabatan berhasil diubah.","data": null}
     */
    public function update(UpdatePositionRequest $request)
    {
        try {
            DB::beginTransaction();
            $position = DB::table('positions')
                ->select(
                    'id'
                )
                ->where('id', $this->request->id)
                ->first();

            if (!$position) {
                return $this->response(404, 'Jabatan tidak ditemukan.');
            }

            // check if reduction available
            if (sizeof($this->request->position_echelons)) {
                foreach ($this->request->position_echelons as $key => $value) {
                    $countAvailable = DB::table('users as u')
                        ->where('u.position_id', $this->request->id)
                        ->where('u.echelon_id', $value['echelon_id'])
                        ->whereIn('u.employment_status', [1, 6])
                        ->whereNot('employment_type_id', 16) // Exclude employment_type_id = 16 (TNP2K) when count
                        ->count();

                    if ($countAvailable > $value['available']) {
                        $echelon = DB::table('echelons as e')
                            ->select('e.name')
                            ->where('e.id', $value['echelon_id'])
                            ->first();
                        return $this->response(404, 'Eselon ' . $echelon->name . ' sudah terisi ' . $countAvailable . ' orang.');
                    }
                }
                //modify request
                $this->request->merge(['available' => 0]);
            } else {
                $countAvailable = DB::table('users as u')
                    ->where('u.position_id', $this->request->id)
                    ->whereIn('u.employment_status', [1, 6])
                    ->whereNot('employment_type_id', 16) // Exclude employment_type_id = 16 (TNP2K) when count
                    ->count();

                if ($countAvailable > $this->request->available) {
                    $countAvailable = DB::table('users as u')
                        ->where('u.position_id', $this->request->id)
                        ->whereIn('u.employment_status', [1, 6])
                        ->whereNot('employment_type_id', 16) // Exclude employment_type_id = 16 (TNP2K) when count
                        ->count();
                    return $this->response(404, 'Posisi sudah terisi ' . $countAvailable . ' orang.');
                }
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

    /**
     * Delete Positions by ID
     *
     * Delete position with specific ID.
     * @subgroup Position
     * @authenticated
     * @urlParam id integer Refers to the id of position. Example: 1
     * @response 200 {"code":200,"message":"Jabatan berhasil dihapus.","data":null}
     *
     */
    public function delete()
    {
        try {
            $position = DB::table('positions')
                ->select('id')
                ->where('id', $this->request->id)
                ->first();

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

    /**
     * Available Order by parent ID
     *
     * Get available of position for specific Parent ID.
     * @subgroup Position
     * @authenticated
     * @queryParam id integer Refers to the id of parent position. Example: 1
     * @response 200 {"code":200,"message":"success","data":{"4":5,"5":6,"6":7,"7":8,"8":9,"9":10,"10":11,"11":12,"12":13,"13":14,"14":15,"15":16,"16":17,"17":18,"18":19,"19":20}}
     *
     */
    public function availableOrder()
    {
        try {
            $positions = DB::table('positions')
                ->select('id', 'horizontal_order', 'vertical_order')
                ->where('positions.type', '!=', 3)
                ->where('parent_id', $this->request->id)
                ->get();

            if (isset($this->request->id)) {
                $existOrder = $positions->pluck('horizontal_order')->toArray();
            } else {
                $existOrder = $positions->pluck('vertical_order')->toArray();
            }

            $allowedOrder = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
            $availableOrder = array_filter($allowedOrder, function ($item) use ($existOrder) {
                return !in_array($item, $existOrder);
            });

            return $this->response(200, 'success', array_values($availableOrder));
        } catch (\Throwable $th) {
            Log::warning($th);
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }
}
