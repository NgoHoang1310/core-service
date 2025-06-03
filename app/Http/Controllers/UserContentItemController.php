<?php

namespace App\Http\Controllers;


use App\Http\Resources\MovieResource;
use App\Http\Resources\SeriesResource;
use App\Models\Movie;
use App\Models\Series;
use App\Models\UserContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserContentItemController extends ApiController
{
    public function showByUserUuid(Request $request)
    {
        $movies = Movie::query()
            ->join('user_content_item', 'user_content_item.target_id', '=', 'movie.id')
            ->where('user_content_item.user_uuid', $request->user_uuid)
            ->where('user_content_item.target_type', 0)
            ->with(['categories', 'genres', 'videoQualities', 'watchHistory'])
            ->select('movie.*')
            ->get();

        $series = Series::query()
            ->join('user_content_item', 'user_content_item.target_id', '=', 'series.id')
            ->where('user_content_item.user_uuid', $request->user_uuid)
            ->where('user_content_item.target_type', 1)
            ->with(['categories', 'genres', 'watchHistory'])
            ->select('series.*')
            ->get();


        $movieResources = MovieResource::collection($movies);
        $seriesResources = SeriesResource::collection($series);

        $contents = $movieResources->merge($seriesResources)->sortBy('created_at')->values();
        return $this->successResponse(['data' => $contents], 'User content items retrieved successfully');
    }

    public function store(Request $request)
    {
        $userContentItem = UserContentItem::firstOrCreate(
            ['user_uuid' => $request->user_uuid, 'target_id' => $request->target_id, 'target_type' => $request->target_type],
            ['user_uuid' => $request->user_uuid, 'target_id' => $request->target_id, 'target_type' => $request->target_type]
        );

        return $this->successResponse($userContentItem, 'User content item created successfully');
    }

    public function destroy(Request $request)
    {
        $userContentItem = UserContentItem::where('user_uuid', $request->user_uuid)
            ->where('target_id', $request->target_id)
            ->where('target_type', $request->target_type)
            ->first();

        if ($userContentItem) {
            $userContentItem->delete();
            return $this->successResponse([], 'User content item deleted successfully');
        }

        return $this->errorResponse('User content item not found', 404);
    }
}
