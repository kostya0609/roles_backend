<?php
namespace App\Modules\Roles\Facades;

use Illuminate\Support\Facades\Facade;
use App\Modules\Roles\Services\DynamicRoleService;

/**
 * @method static getUsersByRoleId(int $dynamic_role_id, int $user_id): Illuminate\Support\Collection
 */
class DynamicRoleFacade extends Facade
{
	public static function getFacadeAccessor()
	{
		return DynamicRoleService::class;
	}
}