<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class BaseController extends Controller
{
    /**
     * @param string $message
     * @param int $code
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond(string $message, int $code, array $data = [])
    {
        $payload = [];

        $payload['message'] = $message;
        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $code);
    }

    /**
     * Generates the pagination meta from the paginator object.
     *
     * @param LengthAwarePaginator $objects
     * @return array
     */
    protected function generateMeta(LengthAwarePaginator $objects): array
    {
        return [
            'total' => $objects->total(),
            'limit' => $objects->perPage(),
            'has_next' => $objects->hasMorePages(),
            'current_page' => $objects->currentPage(),
            'next_page_url' => $objects->nextPageUrl(),
            'previous_page_url' => $objects->previousPageUrl()
        ];
    }
}
