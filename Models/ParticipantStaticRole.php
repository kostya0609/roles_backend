<?php

namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParticipantStaticRole extends Model
{
	protected $table = 'l_roles_statics_participants';
	protected $with = ['user'];
	protected $fillable = [
		'static_role_id',
		'user_id',
	];
	public $timestamps = false;

	public function user(): HasOne
	{
		return $this->hasOne(User::class, 'ID', 'user_id');
	}
}
