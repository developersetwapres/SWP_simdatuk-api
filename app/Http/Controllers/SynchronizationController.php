<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/*
 * @group Employee
 */
class SynchronizationController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Synchronization Employee Data
     *
     * Synchronization data employee.
     * @group Employee
     * @authenticated
     * @response 200 {"code": 200,"message": "Pegawai berhasil disinkronisasi.","data": null}
     */
    public function index()
    {
        try {
            $accessToken = $this->getAccessToken();
            $dataPegawai = $this->getPegawai($accessToken);
            foreach ($dataPegawai['data'] as $item) {
                $user = DB::table('users')->where('employee_id_number', $item['nipbaru'])->updateTs([
                    'employee_registration_number' => ($item['niplama'] == '0000-00-00') ? null : $item['niplama'],
                    'cpns_effective_date' => ($item['tmtcpns'] == '0000-00-00') ? null : $item['tmtcpns'],
                    'pns_effective_date' => ($item['tmtpns'] == '0000-00-00') ? null : $item['tmtpns'],
                    'office_email' => $item['email_dinas'],
                ]);
            }
            return $this->response(200, 'Pegawai berhasil disinkronisasi.');
        } catch (\Throwable $th) {
            return $this->response(400, 'Gagal melakukan sinkronisasi.');
        }
    }

    /**
     * get access token
     *
     * @return void
     */
    private function getAccessToken()
    {
        $response = Http::asForm()->post(env('SIMSDM_URL') . '/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('SIMSDM_CLIENT_ID'),
            'client_secret' => env('SIMSDM_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'];
        }

        // Log the error response
        \Log::error('Failed to obtain access token', ['response' => $response->body()]);

        throw new \Exception('Unable to obtain access token');
    }

    /**
     * get pegawai data
     *
     * @param string $accessToken
     * @return void
     */
    private function getPegawai($accessToken)
    {
        $response = Http::withToken($accessToken)->get(env('SIMSDM_URL') . '/pegawai/v1/simdatuk/pegawaiAll');
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Unable to obtain access token');
    }
}
