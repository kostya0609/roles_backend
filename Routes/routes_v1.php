<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Roles\Controllers\v1\{StaticRoleController, DynamicRoleController, StaticRolePartitionController};

Route::prefix('/roles/v1')->group(function () {
	Route::prefix('/statics')->group(function () {
		Route::post('/create', StaticRoleController::class . '@create');
		Route::post('/get', StaticRoleController::class . '@getById');
		Route::post('/update', StaticRoleController::class . '@update');
		Route::post('/delete', StaticRoleController::class . '@delete');
		Route::post('/search', StaticRoleController::class . '@search');
		Route::post('/search-v2', StaticRoleController::class . '@searchV2');
		Route::post('/get-users-by-role-id', StaticRoleController::class . '@getUsersByRoleId');
		Route::post('/get-lazy-tree', StaticRoleController::class . '@getLazyTree');
		Route::post('/search-lazy-tree', StaticRoleController::class . '@searchLazyTree');
		Route::post('get-static-role-by-parent-id', StaticRoleController::class . '@getStaticRoleByParentId');		
	});

	Route::prefix('/static-partitions')->group(function () {
		Route::post('/get-all', StaticRolePartitionController::class . '@getAll');
		Route::post('/get-tree', StaticRolePartitionController::class . '@getTree');
		Route::post('/get-breadcrumbs', StaticRolePartitionController::class . '@getBreadcrumbs');		
		Route::post('/create', StaticRolePartitionController::class . '@create');
		Route::post('/edit', StaticRolePartitionController::class . '@edit');
		Route::post('/delete', StaticRolePartitionController::class . '@delete');
	});

	Route::prefix('/dynamics')->group(function () {
		Route::post('/create', DynamicRoleController::class . '@create');
		Route::post('/list', DynamicRoleController::class . '@getAll');
		Route::post('/get', DynamicRoleController::class . '@getById');
		Route::post('/update', DynamicRoleController::class . '@update');
		Route::post('/search', DynamicRoleController::class . '@search');
		Route::post('/search-v2', DynamicRoleController::class . '@searchV2');
		Route::post('/check-role', DynamicRoleController::class . '@checkDynamicRole');
	});
});
