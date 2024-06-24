<?php
namespace App\Modules\Roles\Seeders\Test;

use App\Modules\Roles\Models\StaticRole;
use App\Modules\Roles\Seeders\SeederInterface;

class StaticRoleSeeder implements SeederInterface
{
	function run()
	{
		/**
		 * TODO: Добавить создание связей (участники)
		 */
		$data = [
			[
				'title' => 'Директор ДПУ',
				'is_active' => true,
				'description' => '',
				'creator_id' => 1,
			],
		];

		StaticRole::upsert($data, ['id'], ['title', 'is_active', 'description', 'creator_id', 'editor_id']);
	}
}
