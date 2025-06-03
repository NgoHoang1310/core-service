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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Season
 *
 * @property int $id
 * @property int $series_id
 * @property int $season_number
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property Carbon|null $release
 * @property string|null $poster_url
 * @property string|null $trailer_url
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Episode[] $episodes
 *
 * @package App\Models
 */
class Season extends Model
{
    use HasFactory, Base;

    protected $table = 'season';

    protected $casts = [
        'series_id' => 'int',
        'season_number' => 'int',
        'release' => 'datetime',
        'status' => 'int'
    ];

    protected $fillable = [
        'series_id',
        'season_number',
        'title',
        'slug',
        'description',
        'release',
        'poster_url',
        'trailer_url',
        'status'
    ];

    protected static function booted()
    {
        static::deleting(function ($season) {
            $season->episodes()->delete(); // Xóa các bản ghi liên kết
        });
    }

    /**
     * Get the series this season belongs to.
     *
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the episodes of this season.
     *
     * @return HasMany
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    /**
     * Check if this season has any episodes.
     *
     * @return bool
     */
    public function hasEpisodes(): bool
    {
        return $this->episodes()->exists();
    }

    /**
     * Count the number of episodes in this season.
     *
     * @return int
     */
    public function countEpisodes(): int
    {
        return $this->episodes()->count();
    }

}
