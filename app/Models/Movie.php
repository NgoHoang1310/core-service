<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseTrait as Base;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string $uuid
 * @property int|null $age
 * @property string|null $description
 * @property string|null $release
 * @property int|null $duration
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
 * @property Category[] $categories
 * @property Collection|Genres[] $genres
 * @property Collection|Video_Quality[] $videoQualities
 * @property Watch_History|null $watchHistory
 */
class Movie extends Model
{
    use HasFactory, Base;

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
    protected $table = 'movie';
    protected $fillable = [
        'title',
        'slug',
        'uuid',
        'age',
        'description',
        'release',
        'duration',
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
        static::deleting(function ($movie) {
            $movie->categories()->detach(); // Xóa các bản ghi liên kết
            $movie->genres()->detach(); // Xóa các bản ghi liên kết
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

    public function videoQualities()
    {
        return $this->hasMany(Video_Quality::class, 'video_id');
    }

    public function watchHistory()
    {
        return $this->hasOne(Watch_History::class, 'target_id')
            ->where('target_type', Movie::CONTENT_TARGET_TYPE_MOVIE);
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
