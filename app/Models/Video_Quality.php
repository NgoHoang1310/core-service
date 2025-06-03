<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BaseTrait as Base;

/**
 * App\Models\Video_Quality
 *
 * @property int $id
 * @property int $video_id
 * @property string $video_type
 * @property int $quality
 * @property string|null $video_url
 * @property string|null $metadata
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Video_Quality extends Model
{
    const QUALITY_1080P = 0;
    const QUALITY_720P = 1;
    const QUALITY_480P = 2;
    const MASTER_QUALITY = 3;
    const MASTER_QUALITY_720P = 4;
    const MASTER_QUALITY_480P = 5;

    public static array $arrQuality = [
        self::QUALITY_1080P => '1080p',
        self::QUALITY_720P => '720p',
        self::QUALITY_480P => '480p',
    ];

    const STATUS_PROCESSING = 0;
    const STATUS_READY = 1;
    const STATUS_FAILED = 2;
    const STATUS_UNKNOWN = -1;

    public static array $arrStatusVideo = [
        self::STATUS_PROCESSING => 'Đang xử lý',
        self::STATUS_READY => 'Sẵn sàng',
        self::STATUS_FAILED => 'Thất bại',
        self::STATUS_UNKNOWN => 'Không xác định',
    ];

    public static array $arrStatusVideoLabel = [
        self::STATUS_READY => 'bg-success',
        self::STATUS_PROCESSING => 'bg-warning',
        self::STATUS_FAILED => 'bg-danger',
        self::STATUS_UNKNOWN => 'bg-secondary',
    ];

    use HasFactory, Base;
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'video_quality';
    protected $fillable = [
        'video_id',
        'video_type',
        'quality',
        'video_url',
        'metadata',
        'status',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'video_id');
    }

    public static function getLevelFromQuality(int $quality): int
    {
        $map = [
            self::QUALITY_480P => 1,
            self::QUALITY_720P => 2,
            self::QUALITY_1080P => 3,

            self::MASTER_QUALITY_480P => 1,
            self::MASTER_QUALITY_720P => 2,
            self::MASTER_QUALITY => 3,
        ];

        return $map[$quality] ?? 0;
    }
}
