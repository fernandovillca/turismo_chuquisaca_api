<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MunicipalityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    /*
    public function paginationInformation($request, $paginated, $default)
    {
        return [
            // 'links' => $default['links'],
            'meta' => [
                // 'current_page' => $default['meta']['current_page'],
                'page' => $default['meta']['current_page'],
                'from' => $default['meta']['from'],
                'last_page' => $default['meta']['last_page'],
                'per_page' => $default['meta']['per_page'],
                // 'to' => $default['meta']['to'],
                'total' => $default['meta']['total'],
            ],
        ];
    }
        */
}
