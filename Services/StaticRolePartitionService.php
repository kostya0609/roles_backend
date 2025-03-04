<?php

namespace App\Modules\Roles\Services;

use App\Modules\Roles\Dto\{CreatePartitionDto, EditPartitionDto};
use Illuminate\Support\Collection;
use App\Modules\Roles\Models\{StaticRolePartition, StaticRoleTree};

class StaticRolePartitionService
{
    public function getAll(): Collection
	{
		return  StaticRolePartition::get();		
	}

	public function getTree(): Collection
	{
		$tree = StaticRoleTree::query()
			->whereNull('parent_id')
			// ->with(['staticRoles'])
			->get();

		return $tree;

	}

	public function getBreadcrumbs(?int $partition_id): Collection
	{
		$breadcrumbs = collect([]);

		if (is_null($partition_id)) {

			$breadcrumbs->prepend(new StaticRolePartition(['title' => 'Статичные роли', 'parent_id' => null]));

		} else {

			$partition = StaticRolePartition::findOrFail($partition_id);
			$breadcrumbs->prepend($partition);
			while ($partition->parent_id) {
				$partition = StaticRolePartition::findOrFail($partition->parent_id);
				$breadcrumbs->prepend($partition);
			}
			$breadcrumbs->prepend(new StaticRolePartition(['title' => 'Статичные роли', 'parent_id' => null]));

		}

		return $breadcrumbs;
	}



	public function create(CreatePartitionDto $dto): StaticRolePartition
	{
		$partition = new StaticRolePartition();

		$partition->title = $dto->title;
		$partition->parent_id = $dto->parent_id ?? null;

		$partition->creator_id = $dto->user_id;
		$partition->editor_id = $dto->user_id;
		$partition->is_active = $dto->is_active;

		$partition->save();

		return $partition;
	}

	public function edit(EditPartitionDto $dto): StaticRolePartition
	{
		$partition = StaticRolePartition::find($dto->id);

		if (!$partition) {
			throw new \Exception("Не удалось найти раздел по id $dto->id");
		}

		if (isset($dto->title)) {
			$partition->title = $dto->title;
		}


		if (isset($dto->parent_id)) {
			$partition->parent_id = $dto->parent_id;
		}

		$partition->editor_id = $dto->user_id;
		$partition->is_active = $dto->is_active;

		$partition->save();

		return $partition;
	}

	public function delete(int $id)
	{
		return \DB::transaction(function () use ($id) {

			$partition = StaticRolePartition::with(['staticRoles'])->find($id);

			if (!$partition) {
				throw new \LogicException('Раздел не найден!');
			}

			try{				
                $partition->delete();
			}
			catch(\Illuminate\Database\QueryException $e) {
			
				if ($e->getCode() == 23000) { // код означает нарушение ограничения целостности
					throw new \LogicException('Невозможно удалить раздел, который содержит роли!');
				} 

				throw $e;				
			}		

		});
	}
    
}