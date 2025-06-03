<?php
namespace App\Http\Resources;

use App\Facades\Firebase;
use App\Models\Movie;
use App\Models\Subscription;
use App\Models\Video_Quality;
use App\Models\Watch_History;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        $maxQuality = optional(
            Subscription::query()
                ->where('user_uuid', $request->user_uuid)
                ->where('status', Subscription::STATUS_ACTIVE)
                ->with('plan')
                ->latest()
                ->first()
        )->plan->max_resolution ?? Video_Quality::QUALITY_480P;

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
            'video_qualities' => collect($this->videoQualities)
                ->filter(function ($item) use ($maxQuality) {
                    $level = Video_Quality::getLevelFromQuality($item->quality);
                    $maxLevel = Video_Quality::getLevelFromQuality($maxQuality);

                    // Bản thường (quality: 0, 1, 2)
                    if ($item->quality === Video_Quality::QUALITY_1080P ||
                        $item->quality === Video_Quality::QUALITY_720P ||
                        $item->quality === Video_Quality::QUALITY_480P) {
                        return $level <= $maxLevel;
                    }

                    // Bản master tương ứng (chỉ lấy đúng level)
                    if (in_array($item->quality, [
                        Video_Quality::MASTER_QUALITY,
                        Video_Quality::MASTER_QUALITY_720P,
                        Video_Quality::MASTER_QUALITY_480P,
                    ])) {
                        return $level === $maxLevel;
                    }

                    return false;
                })
                ->values(),
            'duration' => $this->duration,
            'status' => $this->status,
            'type' => Movie::CONTENT_TARGET_TYPE_MOVIE,
            'watch_history' => new WatchHistoryResource($this->whenLoaded('watchHistory')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
