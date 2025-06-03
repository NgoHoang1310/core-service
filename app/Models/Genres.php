<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseTrait as Base;

/**
 * Class Genres
 * @package App\Models\Genres
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Genres extends Model
{
    use HasFactory, Base;
    protected $table = 'genres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
