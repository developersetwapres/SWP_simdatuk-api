<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function errResponse(int $code, string $message): array
    {
        $resp = [
            'code' => $code,
            'status' => '',
            'errors' => $message,
            'data' => null
        ];

        switch ($code) {
            case 400:
                $resp['status'] = 'bad request';
                break;
            case 401:
                $resp['status'] = 'unauthorized';
                break;
            case 403:
                $resp['status'] = 'forbidden';
                break;
            case 404:
                $resp['status'] = 'not found';
                break;
            default:
                $resp['status'] = 'internal server error';
                break;
        }

        return $resp;
    }
}
