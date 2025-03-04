<?php

namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property boolean $is_active
 * @property string $description
 * @property int $creator_id
 * @property int $editor_id
 * @property string $created_at
 * @property string $updated_at
 */
class DynamicRole extends Model
{
	protected $table = 'l_roles_dynamics';
	protected $fillable = [
		'title',
		'is_active',
		'description',
		'editor_id',
		'creator_id',
		'can_check',
	];
	protected $casts = [
		'is_active' => 'boolean',
		'can_check' => 'boolean',
	];
}
