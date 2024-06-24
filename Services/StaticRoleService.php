<?php
namespace App\Modules\Roles\Services;

use Illuminate\Support\Collection;
use App\Modules\Roles\Models\{StaticRole, ParticipantStaticRole, User};
use App\Modules\Roles\Dto\{FilterDto, UpdateStaticRoleDto, CreateStaticRoleDto};

class StaticRoleService
{
	protected FilterService $filterService;

	public function __construct(FilterService $filterService)
	{
		$this->filterService = $filterService;
	}

	public function create(CreateStaticRoleDto $dto): StaticRole
	{
		return \DB::transaction(function () use ($dto): StaticRole {
			$role = new StaticRole();
			$role->title = $dto->title;
			$role->is_active = $dto->is_active;
			$role->description = $dto->description;
			$role->creator_id = $dto->creator_id;
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

	public function getAll(FilterDto $dto): object
	{
		$roles = StaticRole::orderBy($dto->sort, $dto->order);

		$total = $roles->count();
		$roles = $roles->offset($dto->offset)->limit($dto->limit);
		$roles = $roles->get();

		return (object) [
			'items' => $roles,
			'total' => $total,
		];
	}

	public function getById(int $id)
	{
		$role = StaticRole::with(['participants'])->find($id);

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

	public function search(string $q)
	{
		$str = explode(' ', trim($q));
		$result = StaticRole::where(function ($query) use ($str) {
			foreach ($str as $word) {
				if (!empty ($word)) {
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

	public function getUsersByRoleId(int $id): Collection
	{
		$users_id = ParticipantStaticRole::where('static_role_id', '=', $id)->get();
		return User::find($users_id);
	}
}

