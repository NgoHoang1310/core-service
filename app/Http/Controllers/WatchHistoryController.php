<?php

namespace App\Http\Controllers;


use App\Http\Resources\MovieResource;
use App\Http\Resources\SeriesResource;
use App\Models\Movie;
use App\Models\Series;
use App\Models\UserContentItem;
use App\Models\Watch_History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchHistoryController extends ApiController
{
    public function showByUserUuid(Request $request)
    {
        $movies = Movie::query()
            ->join('watch_history', 'watch_history.target_id', '=', 'movie.id')
            ->where('watch_history.user_uuid', $request->user_uuid)
            ->where('watch_history.target_type', Movie::CONTENT_TARGET_TYPE_MOVIE)
            ->where('watch_history.is_finished', 0)
            ->with(['categories', 'genres', 'videoQualities', 'watchHistory'])
            ->select('movie.*')
            ->get();

        $series = Series::query()
            ->join('watch_history', 'watch_history.target_id', '=', 'series.id')
            ->where('watch_history.user_uuid', $request->user_uuid)
            ->where('watch_history.target_type', Series::CONTENT_TARGET_TYPE_SERIES)
            ->where('watch_history.is_finished', 0)
            ->with(['categories', 'genres', 'watchHistory'])
            ->select('series.*')
            ->get();


        $movieResources = MovieResource::collection($movies);
        $seriesResources = SeriesResource::collection($series);

        $contents = $movieResources->merge($seriesResources)->sortBy('created_at')->values();
        return $this->successResponse(['data' => $contents], 'User watch history retrieved successfully');
    }
    public function update(Request $request)
    {
        $watchHistory = Watch_History::updateOrCreate(
            [
                'user_uuid' => $request->user_uuid,
                'target_id' => $request->target_id,
                'target_type' => $request->target_type,
            ],
            [
                'user_uuid' => $request->user_uuid,
                'target_id' => $request->target_id,
                'target_type' => $request->target_type,
                'season_id' => $request->season_id ?? null,
                'episode_id' => $request->episode_id ?? null,
                'progress_seconds' => $request->progress_seconds ?? 0,
                'duration_seconds' => $request->duration_seconds ?? 0,
                'is_finished' => $request->is_finished ?? false,

            ]
        );

        return $this->successResponse($watchHistory, 'Update watch history successfully');
    }
}
