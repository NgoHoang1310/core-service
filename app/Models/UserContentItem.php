<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserContentItem
 * 
 * @property int $id
 * @property string $user_uuid
 * @property int $target_id
 * @property int $target_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserContentItem extends Model
{
	protected $table = 'user_content_item';

	protected $casts = [
		'target_id' => 'int',
		'target_type' => 'int'
	];

	protected $fillable = [
		'user_uuid',
		'target_id',
		'target_type'
	];
}
