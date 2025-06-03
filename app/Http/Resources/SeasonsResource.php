<?php
namespace App\Http\Resources;

use App\Facades\Firebase;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'season_number' => $this->season_number,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'release' => $this->release->format('Y-m-d'),
            'poster_url' => Firebase::getPublicFileUrl($this->poster_url),
            'trailer_url' => $this->trailer_url,
            'status' => $this->status,
        ];
    }
}
