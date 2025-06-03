<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmFilterRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\FilmService;
use Illuminate\Support\Facades\Log;

class MovieController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilmFilterRequest $request)
    {
        $filters = $request->validated();
        $movies = FilmService::filterMovies($filters);
        return $this->successResponse(MovieResource::collection($movies)->response()->getData(true), 'Movies retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showByUuid(string $uuid)
    {
        $movie = Movie::query()->with('categories', 'genres', 'videoQualities')
            ->where('uuid', $uuid)
            ->where('status', Movie::STATUS_ACTIVE)
            ->first();
        return $this->successResponse(new MovieResource($movie), 'Movie retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
