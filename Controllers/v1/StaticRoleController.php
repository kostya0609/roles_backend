<?php
namespace App\Modules\Roles\Controllers\v1;

use App\Modules\Roles\Controllers\BaseController;
use App\Modules\Roles\Dto\{CreateStaticRoleDto, UpdateStaticRoleDto, FilterDto};
use App\Modules\Roles\Services\{StaticRoleService};
use App\Modules\Roles\Requests\{
	FilterRequest,
	GetByIdRequest,
	CreateStaticRoleRequest,
	UpdateStaticRoleRequest,
    GetByQueryRequest
};

class StaticRoleController extends BaseController

{
    protected StaticRoleService $service;

    public function __construct(StaticRoleService $service)
    {
        $this->service = $service;
    }

	public function create(CreateStaticRoleRequest $request)
	{
        $dto = new CreateStaticRoleDto();

        $dto->title = $request->title;
        $dto->description = $request->description;
        $dto->is_active = $request->is_active;
        $dto->creator_id = $request->user_id;
        $dto->users = $request->users;

        $role = $this->service->create($dto);

        return $this->sendResponse($role->id);
	}

	public function getAll(FilterRequest $request)
	{
        $dto = new FilterDto();
        $dto->sort   = $request->sort['name']  ? : 'id';
        $dto->order  = $request->sort['order'] ? : 'desc';
        $dto->user_id = $request->user_id;
        $dto->limit  = $request->count;
        $dto->offset = $dto->limit * ($request->page - 1);

        $roles = $this->service->getAll($dto);

        return $this->sendResponse($roles);

	}

	public function getById(GetByIdRequest $request)
	{
        $role = $this->service->getById($request->id);

        return $this->sendResponse($role);
	}

	public function update(UpdateStaticRoleRequest $request)
	{
        $dto = new UpdateStaticRoleDto();

        $dto->id = $request->id;
        $dto->title = $request->title;
        $dto->description = $request->description;
        $dto->is_active = $request->is_active;
        $dto->editor_id = $request->user_id;
        $dto->users = $request->users;

        $role = $this->service->update($dto);

        return $this->sendResponse($role->id);
	}

	public function delete(GetByIdRequest $request)
	{
        $this->service->delete($request->id);

        return $this->sendResponse();
	}

    public function search(GetByQueryRequest $request)
    {
        $roles = $this->service->search($request->q);

        return $this->sendResponse($roles);
    }

}
