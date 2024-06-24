<?php
namespace App\Modules\Roles\Dto;

class CreateDynamicRoleDto
{
	public string $title;
	public bool $is_active;
	public string $description;
	public int $creator_id;
}
