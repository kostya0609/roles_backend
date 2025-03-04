<?php

namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\Department;
use App\Modules\Roles\Models\User;
use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DivisionalSubDirector implements RoleInterface
{
    public function execute(int $user_id, array $params = []): Collection
    {
        $headBranchDepId = 1392;
        $required_deps_id = $this->departmentsHierarchy($headBranchDepId);
        $user_deps = $this->userDepartment($user_id);
        foreach($user_deps as $dep)
        {
            if(in_array($dep['ID'],$required_deps_id))
            {
                $required_deps = $this->allDepartments()->whereIn('ID', $required_deps_id);

                $parent_dep = $required_deps->firstWhere('ID',$dep['ID']);

                $parent_dep_id = $parent_dep->IBLOCK_SECTION_ID;

                while($parent_dep_id != $headBranchDepId)
                {
                    $parent_dep = $required_deps->firstWhere('ID',$parent_dep_id);
                    if($parent_dep->IBLOCK_SECTION_ID == $headBranchDepId)
                    {
                        break;
                    }
                    $parent_dep_id = $parent_dep->IBLOCK_SECTION_ID;
                }

                $required_deps_id = $this->departmentsHierarchy($parent_dep_id);

                $users_id = $this->usersIdInDepartmentsId($required_deps_id);
                $user = User::where([['ACTIVE','=','Y'],['WORK_POSITION','=', 'Заместитель директора дивизиона']])->whereIn('ID',$users_id)->first();

//                $required_user = $user_list_in_required_deps
//                    ->firstWhere('WORK_POSITION','=', 'Заместитель директора дивизиона');
                if($user)
                {
//                    $user = User::find($required_user->ID);
                    return collect([$user]);
                }
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
        $deps_id = DB::table('b_user')
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
            ->where([['b_utm_user.FIELD_ID', 41], ['b_user.ID', $user_id]])->get()
            ->pluck('DEPARTMENT');
        return Department::find($deps_id);
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

    private function allUsers():Collection
    {
        return DB::table('b_user')
            ->where('ACTIVE', '=', 'Y')
            ->join('b_utm_user', 'b_user.ID', '=', 'b_utm_user.VALUE_ID')
            ->select('b_user.ID','b_user.ACTIVE', 'b_user.NAME', 'b_user.LAST_NAME',
                'b_user.PERSONAL_PROFESSION',
                'b_user.WORK_POSITION',
                'b_user.SECOND_NAME', 'b_user.XML_ID', 'b_utm_user.VALUE_INT as DEPARTMENT')
            ->where([['b_utm_user.FIELD_ID', 41]])
            ->get();
    }

    private function usersIdInDepartmentsId(array $deps_id)
    {
        //VALUE_ID = user_id
        //VALUE_INT = department_id
        $users_id = DB::table('b_utm_user')->where('FIELD_ID','=',41)
            ->whereIn('VALUE_INT',$deps_id)->get()->pluck('VALUE_ID');
        return $users_id;
    }
}
