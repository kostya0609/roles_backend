<?php
namespace App\Modules\Roles\Seeders\Initial;

use App\Modules\Roles\Seeders\SeederInterface;
use App\Modules\Roles\Models\DynamicRole;
use App\Modules\Roles\Enums\DynamicRole as DynamicRoleEnum;

class DynamicRoleSeeder implements SeederInterface
{
	function run()
	{
		$data = [
			[
				'id' => DynamicRoleEnum::DIRECT_SUPERVISOR,
				'title' => 'Непосредственный руководитель',
				'is_active' => true,
				'description' => 'Автоматически должна найти руководителя сотрудника.',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::HIERARCHY_SUPERVISORS,
				'title' => 'Иерархия руководителей (включая ГД)',
				'is_active' => true,
				'description' => 'Автоматически должна найти всех руководителей сотрудника вверх по иерархии (не включая ГД).',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::SUBORDINATES,
				'title' => 'Подчиненные',
				'is_active' => true,
				'description' => 'Автоматически должна найти всех подчиненных вниз по иерархии или вернуть себя если данный сотрудник не руководитель.',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::HEAD_DIRECTION,
				'title' => 'Руководитель направления',
				'is_active' => true,
				'description' => 'Возвращает руководителя направления относительно инициатора',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::REGIONAL_DIRECTOR_NRT,
				'title' => 'Региональный директор НРТ',
				'is_active' => true,
				'description' => 'Возвращает руководителя подразделений “Управление торговлей” относительно инициатора в дочерних подразделениях',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::DIVISIONAL_DIRECTOR,
				'title' => 'Директор дивизиона',
				'is_active' => true,
				'description' => 'Возвращает руководителя дивизиона относительно инициатора в дочерних подразделениях',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::DIVISIONAL_SUB_DIRECTOR,
				'title' => 'Заместитель дивизионного директора',
				'is_active' => true,
				'description' => 'Возвращает сотрудника с должностью “Заместитель дивизионного директора” относительно инициатора документа',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::CHIEF_ACCOUNTANT_DEPARTMENT,
				'title' => 'Главный бухгалтер направления',
				'is_active' => true,
				'description' => 'Возвращает сотрудника с должностью “Главный бухгалтер” относительно подразделения инициатора документа',
				'creator_id' => 14317,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::DOCUMENT_INITIATOR,
				'title' => 'Инициатор документа',
				'is_active' => true,
				'description' => 'Определяет и возвращает пользователя, который инициировал создание документа.',
				'creator_id' => 14956,
				'can_check' => false,
			],
            [
				'id' => DynamicRoleEnum::SPECIALIST_OKA,
				'title' => 'Специалист ОКА',
				'is_active' => true,
				'description' => 'Возвращает специалиста ОКА относительно подразделения инициатора документа',
				'creator_id' => 14287,
				'can_check' => true,
			],
			[
				'id' => DynamicRoleEnum::HIERARCHY_SUPERVISORS_WITH_OUT_GD,
				'title' => 'Иерархия руководителей(не включая ГД)',
				'is_active' => true,
				'description' => 'Автоматически должна найти всех руководителей сотрудника вверх по иерархии (не включая ГД).',
				'creator_id' => 14317,
				'can_check' => true,
			],
		];

		DynamicRole::upsert($data, ['id'], ['title', 'is_active', 'description', 'can_check']);
	}
}
