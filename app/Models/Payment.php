<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @property int $id
 * @property string $user_uuid
 * @property int $user_subscription_id
 * @property float $amount
 * @property int $payment_date
 * @property string|null $transaction_id
 * @property string $bank_code
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Payment extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    public static $arrStatus = [
        self::STATUS_PENDING => 'Đang xử lý',
        self::STATUS_SUCCESS => 'Thành công',
        self::STATUS_FAILED => 'Thất bại'
    ];

    protected $table = 'payment';

    protected $casts = [
        'subscription_id' => 'int',
        'amount' => 'float',
        'transaction_id' => 'int',
        'status' => 'int'
    ];

    protected $fillable = [
        'user_uuid',
        'subscription_id',
        'amount',
        'payment_date',
        'transaction_id',
        'bank_code',
        'status'
    ];
}
