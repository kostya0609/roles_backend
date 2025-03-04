<?php
namespace App\Modules\Roles\Dto;

class EditPartitionDto
{
	public int $id;
	public string $title;
	public ?int $parent_id;
	public int $user_id;
	public bool $is_active;
}