<?php
namespace App\Modules\Roles\Controllers\v1;

use App\Modules\Roles\Controllers\BaseController;
use App\Modules\Roles\Dto\{CreateStaticRoleDto, UpdateStaticRoleDto};
use App\Modules\Roles\Services\{StaticRoleService};
use Illuminate\Http\Request;
use App\Modules\Roles\Requests\{
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
		$dto->partition_id = $request->partition_id;

		$role = $this->service->create($dto);

		return $this->sendResponse($role->id);
	}	

	public function getLazyTree(Request $request)
	{
		$roles = $this->service->getLazyTree($request->input('id'));
		return $this->sendResponse($roles);
	}

	public function searchLazyTree (Request $request){
		$roles = $this->service->searchLazyTree($request->input('query'));
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
		$dto->partition_id = $request->partition_id;
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

	public function searchV2(GetByQueryRequest $request)
	{
		$roles = $this->service->searchV2($request->q);

		return $this->sendResponse($roles);
	}

	public function getUsersByRoleId(GetByIdRequest $request)
	{
		$users = $this->service->getUsersByRoleId($request->id);

		return $this->sendResponse($users);
	}

	public function getStaticRoleByParentId()
	{		
		$role = $this->service->getStaticRoleByParentId();
		return $this->sendResponse($role);
	}

}
