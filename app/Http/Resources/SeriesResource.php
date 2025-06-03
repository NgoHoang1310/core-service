<?php

namespace App\Http\Resources;

use App\Facades\Firebase;
use App\Models\Movie;
use App\Models\Series;
use Illuminate\Http\Resources\Json\JsonResource;

class SeriesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'age' => [
                'key' => $this->age,
                'value' => Movie::$arrAge[$this->age],
            ],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'genres' => GenresResource::collection($this->whenLoaded('genres')),
            'directors' => $this->directors,
            'actors' => $this->actors,
            'country' => $this->country,
            'release' => $this->release->format('Y-m-d'),
            'description' => $this->description,
            'rating' => $this->rating,
            'poster_url' => Firebase::getPublicFileUrl($this->poster_url),
            'backdrop_url' => Firebase::getPublicFileUrl($this->backdrop_url),
            'trailer_url' => $this->trailer_url,
            'seasons' => SeasonsResource::collection($this->whenLoaded('seasons')),
            'status' => $this->status,
            'type' => Series::CONTENT_TARGET_TYPE_SERIES,
            'start_from' => optional(optional($this->seasons->first())->episodes)->first(),
            'watch_history' => new WatchHistoryResource($this->whenLoaded('watchHistory')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
