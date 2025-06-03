<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseTrait as Base;

/**
 * Class Category
 * @package App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Movie[] $movies
 */

class Category extends Model
{
    use HasFactory, Base;
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'content_category');
    }

}
