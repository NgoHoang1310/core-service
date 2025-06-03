<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmFilterRequest;
use App\Http\Resources\SeriesResource;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use App\Services\FilmService;
use Illuminate\Http\Request;

class SeriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilmFilterRequest $request)
    {
        $filters = $request->validated();
        $series = FilmService::filterSeries($filters);

        return $this->successResponse(SeriesResource::collection($series)->response()->getData(true), 'Series retrieved successfully');
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
    public function getEpisodesBySeason(Series $series, Season $season, Request $request)
    {

        $query = Episode::query()->with('videoQualities')
            ->where('series_id', $series->id)
            ->where('season_id', $season->id);

        // Phân trang
        $perPage = $request->input('per_page', 10);
        $episodes = $query->paginate($perPage);

        return $this->successResponse($episodes, 'Episodes retrieved successfully');
    }

    public function getEpisodesBySeries(Series $series, Request $request)
    {

        $query = Episode::query()->with('videoQualities')
            ->where('series_id', $series->id)
            ->where('season_id', null);

        // Phân trang
        $perPage = $request->input('per_page', 10);
        $episodes = $query->paginate($perPage);

        return $this->successResponse($episodes, 'Episodes retrieved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function showByUuid(string $uuid)
    {
        $movie = Series::query()->with('categories', 'genres', 'seasons')
            ->where('uuid', $uuid)
            ->where('status', Series::STATUS_ACTIVE)
            ->first();
        return $this->successResponse(new SeriesResource($movie), 'Series retrieved successfully');
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
