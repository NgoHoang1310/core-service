<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BaseTrait as Base;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class Content_Category
 * @package App\Models\Content_Category
 *
 * @property int $id
 * @property int $target_id
 * @property int $target_type
 * @property int $category_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Content_Category extends Pivot
{
    use HasFactory, Base;
    public $timestamps = true;
    protected $table = 'content_category';
    protected $fillable = ['target_id', 'category_id', 'target_type'];
}
