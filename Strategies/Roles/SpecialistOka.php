<?php

namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\Department;
use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Roles\Models\User;


class SpecialistOka implements RoleInterface
{
    public function execute(int $user_id, array $params = []): Collection
    {
        $specialist_1_id = 13598;//Гелета
        $specialist_2_id = 13325;//Глухова
        $specialist_3_id = 15466;//Грошева

//        if($user_id == 6145)
//        {
//            return collect([User::find($specialist_3_id)]);
//        }
        $user_dep_id = $this->userDepartment($user_id)['ID'];


        $arr_tree_deps_id_in_specialist_1 = [1347,1573,43640,1553,43876, 1518];
        $arr_all_deps_id_in_specialist_1 = [];
        foreach($arr_tree_deps_id_in_specialist_1 as $dep_id)
        {
            $item_hierarchy = $this->departmentsHierarchy($dep_id);
            $item_hierarchy[] = $dep_id;
            if(in_array($user_dep_id,$item_hierarchy))
            {
                return collect([User::find($specialist_1_id)]);
            }
            $arr_all_deps_id_in_specialist_1 = array_merge($arr_all_deps_id_in_specialist_1,$item_hierarchy);
        }


        $arr_tree_deps_id_in_specialist_2 = [1528];
        $arr_all_deps_id_in_specialist_2 = [];
        foreach($arr_tree_deps_id_in_specialist_2 as $dep_id)
        {
            $item_hierarchy = $this->departmentsHierarchy($dep_id);
            $item_hierarchy[] = $dep_id;

            $arr_all_deps_id_in_specialist_2 = array_merge($arr_all_deps_id_in_specialist_2,$item_hierarchy);
        }
        $arr_all_deps_id_in_specialist_2 = array_diff($arr_all_deps_id_in_specialist_2,$arr_all_deps_id_in_specialist_1);
        if(in_array($user_dep_id,$arr_all_deps_id_in_specialist_2))
        {
            return collect([User::find($specialist_2_id)]);
        }


        $arr_tree_deps_id_in_specialist_3 = [1373];
        foreach($arr_tree_deps_id_in_specialist_3 as $dep_id)
        {
            $item_hierarchy = $this->departmentsHierarchy($dep_id);
            $item_hierarchy[] = $dep_id;

            if(in_array($user_dep_id,$item_hierarchy))
            {
                return collect([User::find($specialist_3_id)]);
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

    private function departmentsHierarchy($dep_id): array
    {
        $departmentsId = [];
        $allDeps = $this->allDepartments();

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
}
