<?php

namespace App\Helpers;

use Illuminate\Http\Response;

trait Responser
{
    /**
     * Structuring of JSON single response for API.
     *
     * @param integer $code
     * @param string $message
     * @param object|array $data
     * @return void
     */
    public function response($code = 200, $message = 'success', $data = null)
    {
        return response()->json(['code' => $code, 'message' => $message, 'data' => $data], $code);
    }

    /**
     * Structuring of JSON paginate response for API.
     *
     * @param integer $code
     * @param string $message
     * @param array $data
     * @return void
     */
    public function paginateResponse($code = 200, $message = 'success', $data = null)
    {
        $pagination = [
            'total' => $data->total(),
            'count' => $data->lastItem(),
            'per_page' => (int) $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
            'links' => [
                'first_page' => $data->url(1),
                'last_page' => $data->url($data->lastPage()),
                'next_page' => $data->nextPageUrl(),
                'prev_page' => $data->previousPageUrl(),
            ],
        ];
        return response()->json(['code' => $code, 'message' => $message, 'data' => $data->items(), 'pagination' => $pagination], $code);
    }
}
