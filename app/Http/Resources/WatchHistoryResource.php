<?php
namespace App\Http\Resources;

use App\Facades\Firebase;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_uuid' => $this->user_uuid,
            'target_id' => $this->target_id,
            'target_type' => $this->target_type,
            'episode' => $this->episode,
            'progress_seconds' => $this->progress_seconds,
            'duration_seconds' => $this->duration_seconds,
            'is_finished' => $this->is_finished,
        ];
    }
}
