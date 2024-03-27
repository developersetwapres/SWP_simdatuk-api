<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Helpers\Responser;
use App\Http\Requests\CreateJabatanRequest;
use App\Repositories\BagianRepository;
use App\Repositories\BiroRepository;
use App\Repositories\DeputiRepository;
use App\Repositories\EselonRepository;
use App\Repositories\JabatanRepository;
use App\Repositories\SubbagianRepository;

class JabatanController extends Controller
{
    use Responser;

    protected $jabatanRepo;
    protected $eselonRepo;
    protected $deputiRepo;
    protected $biroRepo;
    protected $bagianRepo;
    protected $subbagianRepo;

    public function __construct(
        JabatanRepository $jabatanRepo,
        EselonRepository $eselonRepo,
        DeputiRepository $deputiRepo,
        BiroRepository $biroRepo,
        BagianRepository $bagianRepo,
        SubbagianRepository $subbagianRepo,
    )
    {
        $this->jabatanRepo = $jabatanRepo;
        $this->eselonRepo = $eselonRepo;
        $this->deputiRepo = $deputiRepo;
        $this->biroRepo = $biroRepo;
        $this->bagianRepo = $bagianRepo;
        $this->subbagianRepo = $subbagianRepo;
    }

    /**
     * Get List of Jabatan
     * @group ACL - Access Control List
     * @subgroup Jabatan
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @response 200 {"code": 200, "message": "success", "data": [{
     * "id": 1,
     * "jabatan": "Kepala Subbagian Acara",
     * "eselon": "Eselon IV.a"
     * "deputi": "Deputi Bidang Administrasi"
     * "biro": null
     * }]}
     * @response 401 {"code": 401,"message": "unauthorized", "data": null}
     * @response 403 {"code": 403,"message": "forbidden", "data": null}
     * @response 404 {"code": 404,"message": "not found", "data": null}
     * @response 500 {"code": 500,"message": "internal server error","data": null}
     */
    public function list()
    {
        try {
            $jabatan = $this->jabatanRepo->list();
            if (count($jabatan) === 0) {
                return $this->response(404, 'data jabatan tidak di temukan.');
            }
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse();
        }

        return $this->response(200, 'success', $jabatan);
    }

    /**
     * Create New Jabatan
     * @group ACL - Access Control List
     * @subgroup Jabatan
     * @header Authorization 10|voZgUvHLO3A0EGV7gWurb1MzeKOidjAKk8wR4tCZaec5e35e
     * @bodyParam nama string Nama jabatan. Example: example
     * @bodyParam jumlah_diperlukan integer Jumlah yang diperlukan. Example: 4
     * @bodyParam eselon_id integer Eselon ID. Example: 1
     * @bodyParam deputi_id integer Deputi ID. Example: 1
     * @bodyParam biro_id integer Biro ID. Example: 1
     * @bodyParam bagian_id integer Bagian ID. Example: 1
     * @bodyParam subbagian_id integer Subbagian ID. Example: 1
     * @response 201 {"code": 201, "message": "created", "data": null}
     * @response 400 {"code": 400, "message": "bad request", "data": null}
     * @response 401 {"code": 401, "message": "unauthorized", "data": null}
     * @response 403 {"code": 403, "message": "forbidden", "data": null}
     * @response 404 {"code": 404, "message": "not found", "data": null}
     * @response 500 {"code": 500, "message": "internal server error","data": null}
     */
    public function create(CreateJabatanRequest $request)
    {
        try {
            // siapkan data yang akan di simpan
            $data = [
                'nama' => $request['nama'],
                'jumlah_diperlukan' => $request['jumlah_diperlukan'],
            ];

            // validasi eselon id
            if (isset($request['eselon_id'])) {
                $eselon = $this->eselonRepo->findById($request['eselon_id']);
                if (!$eselon) {
                    return $this->response(404, 'id eselon tidak di temukan');
                }

                $data['eselon_id'] = $request['eselon_id'];
            }

            // validasi deputi id
            if (isset($request['deputi_id'])) {
                $deputi = $this->deputiRepo->findById($request['deputi_id']);
                if (!$deputi) {
                    return $this->response(404, 'id deputi tidak di temukan');
                }

                $data['deputi_id'] = $deputi->id;

                // validasi biro id
                if (isset($request['biro_id'])) {
                    $biro = $this->biroRepo->findById($request['biro_id']);
                    if (!$biro) {
                        return $this->response(404, 'id biro tidak di temukan');
                    }
                    if ($biro->deputi_id != $request['deputi_id']) {
                        return $this->response(400, "{$biro->nama} bukan bagian dari {$deputi->nama}");
                    }

                    $data['biro_id'] = $biro->id;
                }

                // validasi bagian id
                if (isset($request['bagian_id'])) {
                    $bagian = $this->bagianRepo->findById($request['bagian_id']);
                    if (!$bagian) {
                        return $this->response(404, 'id bagian tidak di temukan');
                    }
                    if ($bagian->deputi_id != $request['deputi_id']) {
                        return $this->response(400, "{$bagian->nama} bukan bagian dari {$deputi->nama}");
                    }

                    $data['bagian_id'] = $bagian->id;
                }

                // validasi subbagian id
                if (isset($request['subbagian_id'])) {
                    $subbagian = $this->subbagianRepo->findById($request['subbagian_id']);
                    if (!$subbagian) {
                        return $this->response(404, 'id subbagian tidak di temukan');
                    }
                    if ($subbagian->deputi_id != $request['deputi_id']) {
                        return $this->response(400, "{$subbagian->nama} bukan bagian dari {$deputi->nama}");
                    }

                    $data['subbagian_id'] = $subbagian->id;
                }
            }

            // simpan data ke database
            $this->jabatanRepo->save($data);

        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->response(400, 'nama jabatan sudah terdaftar.');
            }

            return $this->internalServerErrorResponse();
        }

        return $this->response(201, 'created');
    }
}
