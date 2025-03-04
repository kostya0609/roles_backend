<?php
namespace App\Modules\Roles\Strategies;

use App\Modules\Roles\Enums\DynamicRole;
use App\Modules\Roles\Strategies\Roles\{
	ChiefAccountantDepartment,
	DirectSupervisor,
	DivisionalSubDirector,
	DivisionalDirector,
	HierarhySupervisors,
	Subordinates,
	HeadDirection,
	RegionalDirectorNrt,
	DocumentInitiator,
    SpecialistOka,
	HierarhySupervisorsWithOutGD
};

class RoleStrategy
{
	protected array $roles = [
		DynamicRole::DIRECT_SUPERVISOR => DirectSupervisor::class,
		DynamicRole::HIERARCHY_SUPERVISORS => HierarhySupervisors::class,
		DynamicRole::SUBORDINATES => Subordinates::class,
		DynamicRole::HEAD_DIRECTION => HeadDirection::class,
		DynamicRole::REGIONAL_DIRECTOR_NRT => RegionalDirectorNrt::class,
		DynamicRole::DIVISIONAL_DIRECTOR => DivisionalDirector::class,
		DynamicRole::DIVISIONAL_SUB_DIRECTOR => DivisionalSubDirector::class,
		DynamicRole::CHIEF_ACCOUNTANT_DEPARTMENT => ChiefAccountantDepartment::class,
		DynamicRole::DOCUMENT_INITIATOR => DocumentInitiator::class,
		DynamicRole::SPECIALIST_OKA => SpecialistOka::class,
		DynamicRole::HIERARCHY_SUPERVISORS_WITH_OUT_GD => HierarhySupervisorsWithOutGD::class,			
	];

	public function getStrategy(int $role_id): RoleInterface
	{
		if (!isset($this->roles[$role_id])) {
			throw new \LogicException("Не удалось найти обработчик роли #$role_id");
		}

		return \App::make($this->roles[$role_id]);
	}
}
