<?php
namespace App\Modules\Roles\Enums;

class DynamicRole
{
	public const DIRECT_SUPERVISOR = 1;

    public const HIERARCHY_SUPERVISORS = 2;

    public const SUBORDINATES = 3;

    public const HEAD_DIRECTION = 4;

    public const REGIONAL_DIRECTOR_NRT = 5;

    public const DIVISIONAL_DIRECTOR = 6;

    public const DIVISIONAL_SUB_DIRECTOR = 7;

    public const CHIEF_ACCOUNTANT_DEPARTMENT = 8;

	public const DOCUMENT_INITIATOR = 9;

	public const SPECIALIST_OKA = 10;

    public const HIERARCHY_SUPERVISORS_WITH_OUT_GD = 11;
}
