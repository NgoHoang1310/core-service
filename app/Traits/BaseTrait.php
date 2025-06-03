<?php
namespace App\Traits;

trait BaseTrait{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_BLOCKED = 2;

    public static array $arrStatus = [
        self::STATUS_ACTIVE => 'Đang hoạt động',
        self::STATUS_INACTIVE => 'Ngừng hoat động',
        self::STATUS_BLOCKED => 'Bị khóa',
    ];

    public static array $arrStatusLabel = [
        self::STATUS_ACTIVE => 'bg-success',
        self::STATUS_INACTIVE => 'bg-warning',
        self::STATUS_BLOCKED => 'bg-danger',
    ];

    const CONTENT_TARGET_TYPE_MOVIE = 0;
    const CONTENT_TARGET_TYPE_SERIES = 1;

    public static array $arrContentTargetType = [
        self::CONTENT_TARGET_TYPE_MOVIE => 'Phim lẻ',
        self::CONTENT_TARGET_TYPE_SERIES => 'Phim bộ',
    ];

    public function getStatusTextAttribute()
    {
        return self::$arrStatus[$this->status] ?? 'Unknown';
    }

    public function getStatusLabelAttribute()
    {
        return self::$arrStatusLabel[$this->status];
    }
}
