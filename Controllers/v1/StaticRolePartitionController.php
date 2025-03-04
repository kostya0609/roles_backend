<?php
namespace App\Modules\Roles\Controllers\v1;

use App\Modules\Roles\Controllers\BaseController;
use App\Modules\Roles\Requests\{CreatePartitionRequest, EditPartitionRequest};

use App\Modules\Roles\Dto\{CreatePartitionDto, EditPartitionDto};
use App\Modules\Roles\Services\{StaticRolePartitionService};

use Illuminate\Http\Request;

class StaticRolePartitionController extends BaseController{
	protected StaticRolePartitionService $service;

    public function __construct(StaticRolePartitionService $service)
    {
        $this->service = $service;
    }
	
    public function getAll()
	{        
        $partition = $this->service->getAll();
        return $this->sendResponse($partition);
	}

    public function getTree()
	{
		$roles = $this->service->getTree();
		return $this->sendResponse($roles);
	}

    public function getBreadcrumbs(Request $request) {
		$breadcrumbs = $this->service->getBreadcrumbs($request->id);
        return $this->sendResponse($breadcrumbs);
	}
    public function create(CreatePartitionRequest $request)
	{

		$dto = new CreatePartitionDto();

		$dto->parent_id = $request->parent_id;
		$dto->title = $request->title;
		$dto->user_id = $request->user_id;	
		$dto->is_active = $request->is_active;			

		$partition = $this->service->create($dto);

		return $this->sendResponse($partition);
	}

	public function edit(EditPartitionRequest $request)
	{
		$dto = new EditPartitionDto();

		$dto->id = $request->id;
		$dto->title = $request->title;
		$dto->user_id = $request->user_id;		
		$dto->is_active = $request->is_active;		

		$partition = $this->service->edit($dto);

		return $this->sendResponse($partition);
	}

    public function delete(Request $request)
	{        
        $partition = $this->service->delete($request->id);

        return $this->sendResponse($partition);

	}
}