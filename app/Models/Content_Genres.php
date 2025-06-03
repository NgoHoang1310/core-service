<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BaseTrait as Base;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class Content_Genres
 * @package App\Models\Content_Genres
 *
 * @property int $id
 * @property int $target_id
 * @property int $target_type
 * @property int $genres_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Content_Genres extends Pivot
{
    use HasFactory, Base;
    public $timestamps = true;
    protected $table = 'content_genres';
    protected $fillable = ['target_id', 'genre_id', 'target_type'];
}
