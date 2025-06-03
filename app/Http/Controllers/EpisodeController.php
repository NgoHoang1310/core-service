<?php

namespace App\Http\Controllers;


use App\Http\Resources\EpisodesResource;
use App\Models\Episode;

class EpisodeController extends ApiController
{
   public function showByUuid(string $uuid)
   {
       $episode = Episode::query()->with('series', 'series.seasons', 'season', 'videoQualities')
           ->where('uuid', $uuid)
           ->firstOrFail();
         return $this->successResponse(new EpisodesResource($episode), 'Episode retrieved successfully');
   }
}
