<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\BaseTrait as Base;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $duration_days
 * @property int $max_resolution
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subscription[] $subscriptions
 *
 * @package App\Models
 */
class Plan extends Model
{
    use HasFactory, Base;

	protected $table = 'plan';

	protected $casts = [
		'price' => 'float',
		'duration_days' => 'int',
		'max_resolution' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'price',
		'duration_days',
		'max_resolution',
		'status'
	];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_plan')->withTimestamps();
    }
}
