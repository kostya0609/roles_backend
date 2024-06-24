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
			],
            [
                'id' => DynamicRoleEnum::HIERARCHY_SUPERVISORS,
                'title' => 'Иерархия руководителей',
                'is_active' => true,
                'description' => 'Автоматически должна найти всех руководителей сотрудника вверх по иерархии.',
                'creator_id' => 14317,
            ],
		];

		DynamicRole::upsert($data, ['id'], ['title', 'is_active', 'description']);
	}
}
