<?php

namespace App\Http\Controllers;

use App\Helpers\Responser;
use App\Repositories\JabatanRepository;

class JabatanController extends Controller
{
    use Responser;

    protected $jabatanRepo;

    public function __construct(JabatanRepository $jabatanRepo)
    {
        $this->jabatanRepo = $jabatanRepo;
    }

    /**
     * Get List of Permissions
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
}
