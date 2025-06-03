<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\Request;

class GenresController extends ApiController
{
    public function index(Request $request)
    {
        $query = Genres::query();
        $perPage = $request->input('per_page', 10);
        $genres = $query->paginate($perPage);

        return $this->successResponse($genres, 'Genres retrieved successfully');
    }
}
