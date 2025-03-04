<?php
namespace App\Modules\Roles\Strategies;
use Illuminate\Support\Collection;

interface RoleInterface {
	public function execute(int $user_id, array $params = []): Collection;
}