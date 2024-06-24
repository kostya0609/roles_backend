<?php
namespace App\Modules\Roles\Strategies;
use App\Modules\Roles\Enums\DynamicRole;
use App\Modules\Roles\Strategies\Roles\{DirectSupervisor, HierarhySupervisors};

class RoleStrategy
{
	protected array $roles = [
		DynamicRole::DIRECT_SUPERVISOR => DirectSupervisor::class,
        DynamicRole::HIERARCHY_SUPERVISORS =>HierarhySupervisors::class,
	];

	public function getStrategy(int $role_id): RoleInterface
	{
		if (!isset($this->roles[$role_id])) {
			throw new \LogicException("Не удалось найти обработчик роли #$role_id");
		}

		return \App::make($this->roles[$role_id]);
	}
}
