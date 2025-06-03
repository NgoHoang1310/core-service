<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\BaseTrait as Base;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Episode
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $series_id
 * @property int|null $season_id
 * @property int $episode_number
 * @property string $title
 * @property string|null $slug
 * @property string|null $description
 * @property Carbon|null $release
 * @property int|null $duration
 * @property string|null $poster_url
 * @property string|null $trailer_url
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Series|null $series
 * @property Season|null $season
 * @property Collection|Video_Quality[] $videoQualities
 *
 * @package App\Models
 */
class Episode extends Model
{
    use HasFactory, Base;

	protected $table = 'episode';

	protected $casts = [
		'series_id' => 'int',
		'season_id' => 'int',
		'episode_number' => 'int',
		'release' => 'datetime',
		'duration' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'uuid',
		'series_id',
		'season_id',
		'episode_number',
		'title',
		'slug',
		'description',
		'release',
		'duration',
		'poster_url',
        'trailer_url',
		'status'
	];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function videoQualities()
    {
        return $this->hasMany(Video_Quality::class, 'video_id');
    }

    public function getStatusTextAttribute()
    {
        if (!$this->relationLoaded('videoQualities')) {
            $this->load('videoQualities');
        }

        // Lấy tất cả trạng thái từ các video_quality
        $statuses = $this->videoQualities->pluck('status')->unique();

        // Kiểm tra các trạng thái
        if ($statuses->isEmpty()) {
            return Video_Quality::$arrStatusVideo[Video_Quality::STATUS_UNKNOWN]; // Nếu không có video nào
        }

        // Nếu tất cả video_quality đều là 'ready'
        if ($statuses->count() === 1 && $statuses->first() == Video_Quality::STATUS_READY) {
            return Video_Quality::$arrStatusVideo[Video_Quality::STATUS_READY];
        }

        // Nếu có ít nhất một video_quality đang xử lý
        if ($statuses->contains(Video_Quality::STATUS_PROCESSING)) {
            return Video_Quality::$arrStatusVideo[Video_Quality::STATUS_PROCESSING];
        }

        // Nếu có ít nhất một video_quality thất bại
        if ($statuses->contains(Video_Quality::STATUS_FAILED) && !$statuses->contains(Video_Quality::STATUS_PROCESSING)) {
            return Video_Quality::$arrStatusVideo[Video_Quality::STATUS_FAILED];
        }

        return Video_Quality::$arrStatusVideo[Video_Quality::STATUS_UNKNOWN];
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->relationLoaded('videoQualities')) {
            $this->load('videoQualities');
        }

        // Lấy tất cả trạng thái từ các video_quality
        $statuses = $this->videoQualities->pluck('status')->unique();

        // Kiểm tra các trạng thái
        if ($statuses->isEmpty()) {
            return Video_Quality::$arrStatusVideoLabel[Video_Quality::STATUS_UNKNOWN]; // Nếu không có video nào
        }

        // Nếu tất cả video_quality đều là 'ready'
        if ($statuses->count() === 1 && $statuses->first() == Video_Quality::STATUS_READY) {
            return Video_Quality::$arrStatusVideoLabel[Video_Quality::STATUS_READY];
        }

        // Nếu có ít nhất một video_quality đang xử lý
        if ($statuses->contains(Video_Quality::STATUS_PROCESSING)) {
            return Video_Quality::$arrStatusVideoLabel[Video_Quality::STATUS_PROCESSING];
        }

        // Nếu có ít nhất một video_quality thất bại
        if ($statuses->contains(Video_Quality::STATUS_FAILED) && !$statuses->contains(Video_Quality::STATUS_PROCESSING)) {
            return Video_Quality::$arrStatusVideoLabel[Video_Quality::STATUS_FAILED];
        }

        // Trạng thái hỗn hợp
        return Video_Quality::$arrStatusVideoLabel[Video_Quality::STATUS_UNKNOWN];
    }

}
