<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Roles\Controllers\v1\{StaticRoleController, DynamicRoleController};

Route::prefix('/roles/v1')->group(function () {
	Route::prefix('/statics')->group(function () {
		Route::post('/create', StaticRoleController::class . '@create');
		Route::post('/list', StaticRoleController::class . '@getAll');
		Route::post('/get', StaticRoleController::class . '@getById');
		Route::post('/update', StaticRoleController::class . '@update');
		Route::post('/delete', StaticRoleController::class . '@delete');
        Route::post('/search', StaticRoleController::class . '@search');
	});

	Route::prefix('/dynamics')->group(function () {
        Route::post('/create', DynamicRoleController::class . '@create');
		Route::post('/list', DynamicRoleController::class . '@getAll');
		Route::post('/get', DynamicRoleController::class . '@getById');
        Route::post('/update', DynamicRoleController::class . '@update');
        Route::post('/search', DynamicRoleController::class . '@search');
        Route::post('/check-role', DynamicRoleController::class . '@checkDynamicRole');
	});
});
