<?php
namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
;

class StaticRoleTree extends Model
{
	protected $table = 'l_roles_statics_partitions';
	public $timestamps = false;
	protected $fillable = [
		'parent_id',
		'title',
	];
	protected $with = ['children'];

	protected $casts = [
		'is_active' => 'boolean',
	];

	public function children(): HasMany
	{
		return $this
			->hasMany(self::class, 'parent_id')
			->with(['staticRoles']);			
	}

	public function staticRoles(): HasMany
	{
		return $this->hasMany(StaticRole::class, 'partition_id', 'id');
	}
}