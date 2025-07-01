<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Services\Queue\Producers\SocketProducer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $type
 * @property int|null $target_id
 * @property int $is_read
 * @property string|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Notification extends Model
{
    const PERSONAL = 0;
    const GROUP = 1;
    const BROADCAST = 2;
    const ADMIN = 3;

    public static array $arrType = [
        self::PERSONAL  => 'personal',
        self::GROUP     => 'group',
        self::BROADCAST => 'broadcast',
        self::ADMIN     => 'admin',
    ];

	protected $table = 'notification';

	protected $casts = [
		'type' => 'int',
		'target_id' => 'int',
		'is_read' => 'int'
	];

	protected $fillable = [
		'title',
		'content',
		'type',
		'target_id',
		'is_read',
		'payload'
	];

    public static function resolveRoom(int $type, ?int $targetId = null): ?string
    {
        return match ($type) {
            self::PERSONAL  => "user.$targetId",
            self::GROUP     => "group.$targetId",
            self::BROADCAST => "broadcast",
            self::ADMIN     => "role.admin",
            default         => null,
        };
    }
}
