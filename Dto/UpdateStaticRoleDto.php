<?php
namespace App\Modules\Roles\Dto;

class UpdateStaticRoleDto
{
	public int $id;
	public string $title;
	public bool $is_active;
	public string $description;
	public array $users;
	public int $editor_id;
}
