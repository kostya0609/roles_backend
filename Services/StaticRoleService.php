<?php

namespace App\Modules\Roles\Services;

use Illuminate\Support\Collection;
use App\Modules\Roles\Dto\{UpdateStaticRoleDto, CreateStaticRoleDto};
use App\Modules\Roles\Models\{StaticRole, ParticipantStaticRole, User, StaticRolePartitionView, StaticRolePartition};
use App\Modules\BsiTable\FilterFacade;

class StaticRoleService
{
	public function create(CreateStaticRoleDto $dto): StaticRole
	{
		return \DB::transaction(function () use ($dto): StaticRole {
			$role = new StaticRole();
			$role->title = $dto->title;
			$role->is_active = $dto->is_active;
			$role->description = $dto->description;
			$role->creator_id = $dto->creator_id;
			$role->partition_id = $dto->partition_id;
			$role->save();

			$participants = collect($dto->users)->map(function ($user_id) {
				return new ParticipantStaticRole([
					'user_id' => $user_id,
				]);
			});

			$role->participants()->createMany($participants->toArray());

			return $role;
		});
	}


	public function getLazyTree(?int $partition_id = null): Collection
	{
		return StaticRolePartitionView::query()->where('parent_id', $partition_id)->where('is_active', true)->get();
	}

	public function searchLazyTree(?string $query = null): Collection
	{
		return StaticRolePartitionView::query()->where('title', 'like', "%$query%")->where('is_active', true)->get();
	}

	public function getById(int $id)
	{
		$role = StaticRole::with(['participants', 'partition'])->find($id);

		if (!$role) {
			throw new \LogicException('Роль не найдена!');
		}

		return $role;
	}

	public function update(UpdateStaticRoleDto $dto): StaticRole
	{
		return \DB::transaction(function () use ($dto): StaticRole {
			$role = StaticRole::find($dto->id);

			if (!$role) {
				throw new \LogicException('Роль не найдена!');
			}

			$role->title = $dto->title;
			$role->is_active = $dto->is_active;
			$role->description = $dto->description;
			$role->editor_id = $dto->editor_id;
			$role->partition_id = $dto->partition_id;
			$role->save();

			ParticipantStaticRole::where('static_role_id', '=', $dto->id)->delete();

			$participants = collect($dto->users)->map(function ($user_id) {
				return new ParticipantStaticRole([
					'user_id' => $user_id,
				]);
			});

			$role->participants()->createMany($participants->toArray());

			return $role;
		});
	}

	public function delete(int $id): void
	{
		$role = StaticRole::find($id);

		if (!$role) {
			throw new \LogicException('Роль не найдена!');
		}

		$role->delete();
	}

	public function search(?string $q = null)
	{
		$str = explode(' ', trim($q));
		$result = StaticRole::where(function ($query) use ($str) {
			foreach ($str as $word) {
				if (!empty($word)) {
					$query->where('title', 'like', '%' . $word . '%');
				}
			}
		})
			->limit(10)
			->get();

		$roles = [];
		foreach ($result as $el) {
			$roles[] = ['value' => $el->id, 'label' => $el->title];
		}
		return $roles;
	}

	public function searchV2(?string $q = null): Collection
	{
		$str = explode(' ', trim($q));
		return StaticRole::select(['id', 'title', 'description'])
			->where('is_active', true)
			->where(function ($query) use ($str) {
				foreach ($str as $word) {
					if (!empty($word)) {
						$query->where('title', 'like', "%$word%");
					}
				}
			})
			->limit(10)
			->get();
	}

	public function getUsersByRoleId(int $id): Collection
	{
		$users_id = ParticipantStaticRole::where('static_role_id', '=', $id)->get()->pluck('user_id');

		return User::find($users_id);
	}

	public function getStaticRoleByParentId()
	{
		$search_fields = [
			'title' => '%like%',
		];
		
		$custom_sort_fields = [
			'creator' => User::select('LAST_NAME')->whereColumn('b_user.ID', 'l_roles_statics_partitions_view.creator_id'),
			'editor' => User::select('LAST_NAME')->whereColumn('b_user.ID', 'l_roles_statics_partitions_view.editor_id'),
		];

		return FilterFacade::sort($custom_sort_fields)
			->filter()
			->search($search_fields)
			->getAll(StaticRolePartitionView::with(['creator','editor'])->where('parent_id', '=', request()->get('parent_id')));
	}
}