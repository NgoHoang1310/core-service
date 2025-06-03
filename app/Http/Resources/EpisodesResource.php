<?php
namespace App\Http\Resources;
use App\Models\Subscription;

use App\Facades\Firebase;
use App\Models\Video_Quality;
use Illuminate\Http\Resources\Json\JsonResource;

class EpisodesResource extends JsonResource
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
            'series' => new SeriesResource(
                $this->series
            ),
            'season' => $this->whenLoaded('season', function () {
                return [
                    'id' => $this->season->id,
                    'title' => $this->season->title,
                    'number' => $this->season->season_number,
                ];
            }),
            'episode_number' => $this->episode_number,
            'title' => $this->title,
            'duration' => $this->duration,
            'poster_url' => Firebase::getPublicFileUrl($this->poster_url),
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
                ->values()
        ];
    }
}
