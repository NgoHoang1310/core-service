<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\BaseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Voucher
 *
 * @property int $id
 * @property string $code
 * @property string|null $description
 * @property int $type
 * @property float $value
 * @property int|null $max_uses
 * @property int $used_count
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property int $only_for_new_users
 * @property int $only_once_per_user
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Voucher extends Model
{
    use BaseTrait;
    const TYPE_PERCENTAGE = 0;
    const TYPE_FIXED_AMOUNT = 1;
    public static array $arrType = [
        self::TYPE_PERCENTAGE => 'Giảm theo phần trăm',
        self::TYPE_FIXED_AMOUNT => 'Giảm theo số tiền cố định',
    ];

	protected $table = 'voucher';

	protected $casts = [
		'type' => 'int',
		'value' => 'float',
		'max_uses' => 'int',
		'used_count' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'only_for_new_users' => 'int',
		'only_once_per_user' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'code',
		'description',
		'type',
		'value',
		'max_uses',
		'used_count',
		'start_at',
		'end_at',
		'only_for_new_users',
		'only_once_per_user',
		'status'
	];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'voucher_plan');
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_vouchers')->withTimestamps();
    }

    public function getDiscountTypeText()
    {
        return Voucher::$arrType[$this->type];
    }
}
