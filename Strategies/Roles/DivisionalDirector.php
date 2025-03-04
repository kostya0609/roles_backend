<?php

namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\Department;
use App\Modules\Roles\Models\User;
use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DivisionalDirector implements RoleInterface
{

    public function execute(int $user_id, array $params = []): Collection
    {
        //1392 - id НОТ СОП - Сеть обособленных подразделений НОТ
        $headBranchDepId = 1392;
        $required_deps_id = $this->departmentsHierarchy($headBranchDepId);
        $user_dep_id = $this->userDepartment($user_id)['ID'];
        if(in_array($user_dep_id,$required_deps_id))
        {
            $required_deps = $this->allDepartments()->whereIn('ID', $required_deps_id);
            $parent_dep = $required_deps->firstWhere('ID',$user_dep_id);
            $parent_dep_id = $parent_dep->IBLOCK_SECTION_ID;
            if($parent_dep_id == $headBranchDepId)
            {
                $user = User::find($user_id);
                return collect([$user]);
            }
            $required_user_id = '';
            while($parent_dep_id != $headBranchDepId)
            {
                $parent_dep = $required_deps->firstWhere('ID',$parent_dep_id);
                $parent_dep_id = $parent_dep->IBLOCK_SECTION_ID;
                $required_user_id = $parent_dep->HEAD;
            }
            if($required_user_id)
            {
                $required_user = User::find($required_user_id);
                return collect([$required_user]);
            }
        }
        return collect([]);
    }

    private function allDepartments(): Collection
    {
        return DB::table('b_iblock_section')
            ->join('b_uts_iblock_5_section', 'b_iblock_section.ID', '=', 'b_uts_iblock_5_section.VALUE_ID')
            ->where([['b_iblock_section.IBLOCK_ID', 5]])
            ->select('b_iblock_section.ID', 'b_iblock_section.NAME','b_iblock_section.IBLOCK_SECTION_ID', 'b_uts_iblock_5_section.UF_HEAD as HEAD')
            ->get();
    }


    private function userDepartment($user_id)
    {
        $dep_id = DB::table('b_user')
            ->join('b_utm_user', 'b_user.ID', '=', 'b_utm_user.VALUE_ID')
            ->select(
                'b_user.ID',
                'b_user.ACTIVE',
                'b_user.NAME',
                'b_user.LAST_NAME',
                'b_user.SECOND_NAME',
                'b_user.XML_ID',
                'b_utm_user.VALUE_INT as DEPARTMENT'
            )
            ->where([['b_utm_user.FIELD_ID', 41], ['b_user.ID', $user_id]])
            ->first()->DEPARTMENT;
        return Department::find($dep_id);
    }

    private function departmentsHierarchy($dep_id)
    {
        $departmentsId = [$dep_id];
        $allDeps = self::allDepartments();

        $childDepartment = $allDeps
            ->whereIn('IBLOCK_SECTION_ID', $dep_id)
            ->pluck('ID');

        while ($childDepartment->count() > 0) {
            foreach ($childDepartment as $el) {
                $departmentsId[] = $el;
            }
            $childDepartment = $allDeps
                ->whereIn('IBLOCK_SECTION_ID', $childDepartment)
                ->pluck('ID');
        }
        return $departmentsId;
    }
}
