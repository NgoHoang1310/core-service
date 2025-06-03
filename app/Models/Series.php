<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Series
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string $uuid
 * @property string|null $age
 * @property string|null $description
 * @property string|null $release
 * @property int|null $file
 * @property string|null $actors
 * @property string|null $directors
 * @property string|null $country
 * @property string|null $poster_url
 * @property string|null $trailer_url
 * @property string|null $backdrop_url
 * @property float|null $rating
 * @property int $status
 * @property int|null $is_hot
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Collection|Category[] $categories
 * @property Collection|Genres[] $genres
 * @property Collection|Season[] $seasons
 * @property Collection|Episode[] $episodes
 * @property Watch_History|null $watchHistory
 */
class Series extends Model
{
    use HasFactory, BaseTrait;

    const AGE_P = 'P';
    const AGE_K = 'K';
    const AGE_T13 = 'T13';
    const AGE_T16 = 'T16';
    const AGE_T18 = 'T18';
    const AGE_C = 'C';

    public static array $arrAge = [
        self::AGE_P => 'Phổ biến cho mọi đối tượng',
        self::AGE_K => 'Dành cho trẻ em (dưới 13 tuổi)',
        self::AGE_T13 => 'Trẻ em dưới 13 tuổi không được xem nếu không có người lớn đi kèm',
        self::AGE_T16 => 'Cấm trẻ em dưới 16 tuổi',
        self::AGE_T18 => 'Cấm khán giả dưới 18 tuổi',
        self::AGE_C => 'Cấm phổ biến (không được chiếu công khai)',
    ];
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'series';

    protected $fillable = [
        'title',
        'slug',
        'uuid',
        'age',
        'description',
        'release',
        'actors',
        'directors',
        'country',
        'poster_url',
        'trailer_url',
        'backdrop_url',
        'rating',
        'status',
        'is_hot'
    ];

    protected $casts = [
        'release' => 'date',
    ];

    protected static function booted()
    {
        static::deleting(function ($serie) {
            $serie->categories()->detach(); // Xóa các bản ghi liên kết
            $serie->genres()->detach(); // Xóa các bản ghi liên kết
            $serie->seasons()->delete(); // Xóa các bản ghi liên kết
            $serie->episodes()->delete(); // Xóa các bản ghi liên kết
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'content_category', 'target_id', 'category_id')
            ->using(Content_Category::class)
            ->withPivot('target_type')
            ->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genres::class, 'content_genres', 'target_id', 'genre_id')
            ->using(Content_Genres::class)
            ->withPivot('target_type')
            ->withTimestamps();
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function watchHistory()
    {
        return $this->hasOne(Watch_History::class, 'target_id')
            ->where('target_type', Series::CONTENT_TARGET_TYPE_SERIES);
    }

    public function hasSeasons()
    {
        return $this->seasons()->exists();
    }

    public function hasDirectEpisodes()
    {
        return $this->episodes()->exists();
    }

    public function countSeasons()
    {
        return $this->seasons()->count();
    }

    public function countDirectEpisodes()
    {
        return $this->episodes()->count();
    }

}
