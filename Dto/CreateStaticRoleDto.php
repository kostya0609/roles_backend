<?php

namespace App\Modules\Roles\DTO;

class CreateStaticRoleDto
{
	public string $title;
	public bool $is_active;
	public ?string $description = null;
	public int $creator_id;
	public array $users;
	public int $partition_id;
}
