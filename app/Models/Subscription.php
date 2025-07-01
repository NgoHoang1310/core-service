<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 *
 * @property int $id
 * @property string $user_uuid
 * @property int $plan_id
 * @property int|null $voucher_id
 * @property Carbon $start_date
 * @property Carbon $next_bill_at
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \App\Models\Plan|null $plan
 *
 * @package App\Models
 */
class Subscription extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_EXPIRED = 2;
    const STATUS_CANCELED = 3;

    public static $arrStatus = [
        self::STATUS_PENDING => 'Đang xử lí',
        self::STATUS_ACTIVE => 'Đang hoạt động',
        self::STATUS_EXPIRED => 'Hết hạn',
        self::STATUS_CANCELED => 'Đã hủy'
    ];

	protected $table = 'subscription';

	protected $casts = [
		'plan_id' => 'int',
		'start_date' => 'datetime',
		'next_bill_at' => 'datetime',
		'status' => 'int'
	];

	protected $fillable = [
		'user_uuid',
		'plan_id',
		'start_date',
		'next_bill_at',
		'status'
	];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'subscription_vouchers')->withTimestamps();
    }
}
