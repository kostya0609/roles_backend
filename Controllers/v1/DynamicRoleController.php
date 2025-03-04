<?php

namespace App\Modules\Roles\Controllers\v1;

use App\Modules\Roles\Dto\{FilterDto, UpdateDynamicRoleDto, CreateDynamicRoleDto};
use App\Modules\Roles\Controllers\BaseController;
use App\Modules\Roles\Requests\{GetByIdRequest, FilterRequest, GetByQueryRequest, CreateDynamicRoleRequest, UpdateDynamicRoleRequest, CheckDynamicRoleRequest};
use App\Modules\Roles\Services\{DynamicRoleService};

class DynamicRoleController extends BaseController
{
	protected DynamicRoleService $service;

	public function __construct(DynamicRoleService $service)
	{
		$this->service = $service;
	}

	public function getAll(FilterRequest $request)
	{
		$dto = new FilterDto();
		$dto->sort = $request->sort['name'] ?: 'id';
		$dto->order = $request->sort['order'] ?: 'desc';
		$dto->user_id = $request->user_id;
		$dto->limit = $request->count;
		$dto->offset = $dto->limit * ($request->page - 1);

		$roles = $this->service->getAll($dto);

		return $this->sendResponse($roles);

	}

	public function getById(GetByIdRequest $request)
	{
		$role = $this->service->getById($request->id);
		return $this->sendResponse($role);
	}

	public function create(CreateDynamicRoleRequest $request)
	{
		$dto = new CreateDynamicRoleDto();

		$dto->title = $request->title;
		$dto->description = $request->description;
		$dto->is_active = $request->is_active;
		$dto->creator_id = $request->user_id;

		$role = $this->service->create($dto);

		return $this->sendResponse($role->id);
	}

	public function update(UpdateDynamicRoleRequest $request)
	{
		$dto = new UpdateDynamicRoleDto();

		$dto->id = $request->id;
		$dto->title = $request->title;
		$dto->description = $request->description;
		$dto->is_active = $request->is_active;
		$dto->editor_id = $request->user_id;

		$role = $this->service->update($dto);

		return $this->sendResponse($role->id);
	}

	public function search(GetByQueryRequest $request)
	{
		$roles = $this->service->search($request->q);

		return $this->sendResponse($roles);
	}

	public function searchV2(GetByQueryRequest $request)
	{
		$roles = $this->service->searchV2($request->q);

		return $this->sendResponse($roles);
	}

	public function checkDynamicRole(CheckDynamicRoleRequest $request)
	{
		$users = $this->service->getUsersByRoleId($request->id, $request->check_user_id);

		return $this->sendResponse($users);
	}
}
