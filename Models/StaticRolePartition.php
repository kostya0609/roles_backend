<?php
namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaticRolePartition extends Model
{
	protected $table = 'l_roles_statics_partitions';

	protected $casts = [
		'is_active' => 'boolean',
	];

	protected $fillable = [
		'parent_id',
		'title',
		'creator',
		'lastEditor',
		'is_active'
	];

	protected $with = ['staticRoles'];

	public function staticRoles(): HasMany
	{
		return $this->hasMany(StaticRole::class, 'partition_id', 'id');
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id', 'id');
	}
}