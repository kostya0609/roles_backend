<?php

namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Roles\Strategies\RoleInterface;

class Subordinates implements RoleInterface
{
	public function execute(int $user_id, array $params = []): Collection
	{
		$users_ids = [];

		$all_deps = self::allDepartments();

		$boss_deps = $all_deps->where('HEAD', $user_id)->pluck('ID');

		$users_ids = [];

		foreach ($boss_deps as $boss_dep) {

			$departments_id = self::departmentsHierarchy($boss_dep);

			$_users_ids = self::allUsers()
				->whereIn('DEPARTMENT', $departments_id)
				->pluck('ID')
				->toArray();

			$users_ids = array_merge(array_filter($_users_ids, fn($id) => $id != $user_id), $users_ids);
		}

		$users_ids = array_unique($users_ids);

		$users = User::findOrFail($users_ids)->sortBy(function (User $user) use ($users_ids) {
			foreach ($users_ids as $index => $supervisor_id)
				if ($supervisor_id === $user->id)
					return $index;
		});

		return $users->values();
	}

	private function allDepartments(): Collection
	{
		return DB::table('b_iblock_section')
			->join('b_uts_iblock_5_section', 'b_iblock_section.ID', '=', 'b_uts_iblock_5_section.VALUE_ID')
			->where([['b_iblock_section.IBLOCK_ID', 5]])
			->select('b_iblock_section.ID', 'b_iblock_section.NAME', 'b_iblock_section.IBLOCK_SECTION_ID', 'b_uts_iblock_5_section.UF_HEAD as HEAD')
			->get();
	}

	private function allUsers(): Collection
	{
		return DB::table('b_user')
			->where('ACTIVE', '=', 'Y')
			->join('b_utm_user', 'b_user.ID', '=', 'b_utm_user.VALUE_ID')
			->select(
				'b_user.ID',
				'b_user.ACTIVE',
				'b_user.NAME',
				'b_user.LAST_NAME',
				'b_user.SECOND_NAME',
				'b_user.XML_ID',
				'b_utm_user.VALUE_INT as DEPARTMENT'
			)
			->where([['b_utm_user.FIELD_ID', 41]])
			->get();
	}

	private function departmentsHierarchy($dep_id)
	{
		$departmentsId = [$dep_id];
		$allDeps = self::allDepartments();

		$childDepartment = $allDeps
			->whereIn('IBLOCK_SECTION_ID', $dep_id)
			->pluck('ID');

		while ($childDepartment->count() > 0) {
			foreach ($childDepartment as $el) {
				$departmentsId[] = $el;
			}
			$childDepartment = $allDeps
				->whereIn('IBLOCK_SECTION_ID', $childDepartment)
				->pluck('ID');
		}
		return $departmentsId;
	}
}
