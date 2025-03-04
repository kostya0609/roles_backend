<?php
namespace App\Modules\Roles\Services;

use App\Modules\Roles\Models\StaticRole;
use App\Modules\Roles\Models\StaticRolePartition;
use App\Modules\Roles\Models\StaticRolePartitionView;
use Illuminate\Support\Collection;


class StaticRoleTreeCreatorService
{
	public function _createTree(?int $partition_id = null)
	{
		$query = StaticRolePartition::query()->with('children');

		if (is_null($partition_id)) {
			$query->where('parent_id', null);
		} else {
			$query->where('id', $partition_id);
		}

		$partitions = $query->get();

		return $partitions->map(function (StaticRolePartition $partition) {
			$result = new \stdClass;
			$result->item_id = (int) "{$partition->id}1";
			$result->id = $partition->id;
			$result->title = $partition->title;
			$result->isLeaf = false;

			$result->children = collect($partition->children)->map(function (StaticRolePartition $partition) {
				$result = new \stdClass;
				$result->item_id = (int) "{$partition->id}1";
				$result->id = $partition->id;
				$result->title = $partition->title;
				$result->type = 'partition';
				$result->isLeaf = false;
				return $result;
			});

			$result->children = collect($result->children)->merge(
				$partition->staticRoles->map(function (StaticRole $role) {
					$result = new \stdClass;
					$result->item_id = (int) "{$role->id}2";
					$result->id = $role->id;
					$result->title = $role->title;
					$result->type = 'role';
					$result->isLeaf = true;
					return $result;
				})
			);

			return $result;
		});
	}

	public function createTree(?int $partition_id): Collection
	{
		return StaticRolePartitionView::query()->where('parent_id', $partition_id)->where('is_active', true)->get();
	}
}