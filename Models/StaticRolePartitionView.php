<?php

namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class StaticRolePartitionView extends Model
{
	protected $table = 'l_roles_statics_partitions_view';


	public function creator(): HasOne
	{
		return $this->hasOne(User::class, 'ID', 'creator_id');
	}

	public function editor(): HasOne
	{
		return $this->hasOne(User::class, 'ID', 'editor_id');
	}
}
