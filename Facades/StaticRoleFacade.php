<?php
namespace App\Modules\Roles\Facades;

use Illuminate\Support\Facades\Facade;
use App\Modules\Roles\Services\StaticRoleService;

/**
 * @method static getUsersByRoleId(int $static_role_id): Illuminate\Support\Collection
 */
class StaticRoleFacade extends Facade
{
	public static function getFacadeAccessor()
	{
		return StaticRoleService::class;
	}
}