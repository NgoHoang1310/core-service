<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Watch_History
 *
 * @property int $id
 * @property int $user_id
 * @property int $target_id
 * @property int $target_type
 * @property int|null $season_id
 * @property int|null $episode_id
 * @property int $progress_seconds
 * @property int $duration_seconds
 * @property int $is_finished
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Movie|null $movie
 * @property-read \App\Models\Episode|null $episode
 *
 * @package App\Models
 */
class Watch_History extends Model
{
	protected $table = 'watch_history';

	protected $casts = [
		'user_uuid' => 'string',
		'target_id' => 'int',
		'target_type' => 'int',
		'season_id' => 'int',
		'episode_id' => 'int',
		'progress_seconds' => 'int',
		'duration_seconds' => 'int',
		'is_finished' => 'int'
	];

	protected $fillable = [
		'user_uuid',
		'target_id',
		'target_type',
		'season_id',
		'episode_id',
		'progress_seconds',
		'duration_seconds',
		'is_finished'
	];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'target_id')
            ->where('target_type', Movie::CONTENT_TARGET_TYPE_MOVIE); // Assuming 1 is the target type for movies
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class, 'episode_id');
    }
}
