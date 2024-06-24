<?php
namespace App\Modules\Roles\Dto;

class FilterDto
{
	public int $offset;
    public int $limit;
	public string $sort;
    public string $order;
	public int $user_id;
}
