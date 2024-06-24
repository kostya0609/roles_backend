<?php
namespace App\Modules\Roles\Dto;

class UpdateDynamicRoleDto
{
	public int $id;
	public string $title;
	public bool $is_active;
	public string $description;
	public int $editor_id;
}
