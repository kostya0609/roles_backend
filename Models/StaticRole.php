<?php

namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property boolean $is_active
 * @property string $description
 * @property int $creator_id
 * @property int $editor_id
 * @property string $created_at
 * @property string $updated_at
 * @property array $participants
 */
class StaticRole extends Model
{
	protected $table = 'l_roles_statics';
	protected $fillable = [
		'title',
		'is_active',
		'description',
		'creator_id',
		'editor_id',
		'partition_id',
	];
	protected $casts = [
		'is_active' => 'boolean',
	];


	public function creator(): HasOne
	{
		return $this->hasOne(User::class, 'ID', 'creator_id');
	}

	public function editor(): HasOne
	{
		return $this->hasOne(User::class, 'ID', 'editor_id');
	}

	public function participants(): HasMany
	{
		return $this->hasMany(ParticipantStaticRole::class, 'static_role_id', 'id');
	}

	public function partition(): BelongsTo
	{
		return $this->belongsTo(StaticRolePartition::class, 'partition_id', 'id');
	}
}
