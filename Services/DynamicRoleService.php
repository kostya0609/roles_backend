<?php
namespace App\Modules\Roles\Services;

use Illuminate\Support\Collection;
use App\Modules\Roles\Dto\{FilterDto, UpdateDynamicRoleDto, CreateDynamicRoleDto};
use App\Modules\Roles\Models\DynamicRole;
use App\Modules\Roles\Strategies\RoleStrategy;

class DynamicRoleService
{
	protected FilterService $filterService;
	protected RoleStrategy $roleStrategy;

	public function __construct(FilterService $filterService, RoleStrategy $roleStrategy)
	{
		$this->filterService = $filterService;
		$this->roleStrategy = $roleStrategy;
	}

	public function getAll(FilterDto $dto): object
	{
		$roles = DynamicRole::orderBy($dto->sort, $dto->order);

		$total = $roles->count();
		$roles = $roles->offset($dto->offset)->limit($dto->limit);
		$roles = $roles->get();

		return (object) [
			'items' => $roles,
			'total' => $total,
		];
	}

	public function getById(int $id): DynamicRole
	{
		$role = DynamicRole::find($id);

		if (!$role) {
			throw new \LogicException('Роль не найдена!');
		}

		return $role;
	}

	public function create(CreateDynamicRoleDto $dto): DynamicRole
	{
		$role = new DynamicRole();

		$role->title = $dto->title;
		$role->is_active = $dto->is_active;
		$role->description = $dto->description;
		$role->creator_id = $dto->creator_id;
		$role->save();

		return $role;
	}

	public function update(UpdateDynamicRoleDto $dto): DynamicRole
	{
		$role = DynamicRole::find($dto->id);

		if (!$role) {
			throw new \LogicException('Роль не найдена!');
		}

		$role->title = $dto->title;
		$role->is_active = $dto->is_active;
		$role->description = $dto->description;
		$role->editor_id = $dto->editor_id;
		$role->save();

		return $role;
	}

	public function search(?string $q = null): array
	{
		$str = explode(' ', trim($q));
		$result = DynamicRole::where(function ($query) use ($str) {
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
		return DynamicRole::select(['id', 'title', 'description', 'can_check'])
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

	public function getUsersByRoleId(int $role_id, int $check_user_id, array $params = []): Collection
	{
		return $this->roleStrategy->getStrategy($role_id)->execute($check_user_id, $params);
	}
}
