<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmFilterRequest;
use App\Services\FilmService;

class FilmController extends ApiController
{
    public function index(FilmFilterRequest $request)
    {
        $filters = $request->validated();
        $films = FilmService::filterBoth($filters);
        return $this->successResponse([
            'data' => $films->items(),
            'meta' => [
                'current_page' => $films->currentPage(),
                'last_page' => $films->lastPage(),
                'per_page' => $films->perPage(),
                'total' => $films->total(),
            ]
        ], 'Movies retrieved successfully');
    }
}
