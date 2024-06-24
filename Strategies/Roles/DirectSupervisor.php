<?php
namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\Department;
use App\Modules\Roles\Models\User;
use Illuminate\Support\Collection;
use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Facades\DB;

class DirectSupervisor implements RoleInterface
{
    private function allDepartments():Collection{
        return DB::table('b_iblock_section')
            ->join('b_uts_iblock_5_section', 'b_iblock_section.ID', '=', 'b_uts_iblock_5_section.VALUE_ID')
            ->where([['b_iblock_section.IBLOCK_ID', 5]])
            ->select('b_iblock_section.ID', 'b_iblock_section.NAME','b_iblock_section.IBLOCK_SECTION_ID', 'b_uts_iblock_5_section.UF_HEAD as HEAD')
            ->get();
    }

    private function userDepartment($user_id){
        $dep_id = DB::table('b_user')
            ->join('b_utm_user', 'b_user.ID', '=', 'b_utm_user.VALUE_ID')
            ->select('b_user.ID','b_user.ACTIVE', 'b_user.NAME', 'b_user.LAST_NAME',
                'b_user.SECOND_NAME', 'b_user.XML_ID', 'b_utm_user.VALUE_INT as DEPARTMENT')
            ->where([['b_utm_user.FIELD_ID', 41], ['b_user.ID', $user_id]])
            ->first()->DEPARTMENT;
        return Department::find($dep_id);
    }

    public function execute(int $user_id): Collection{
        $all_deps = $this->allDepartments();
        $user_dep_id = $this->userDepartment($user_id)['ID'];

        $user_dep = $all_deps->where('ID', $user_dep_id)->first();
        $direct_supervisor_id = $user_dep->HEAD;
        $parent_dep_id = $user_dep->IBLOCK_SECTION_ID;

        //на случай если искомый пользователь сам является начальником
        if($user_id === $direct_supervisor_id) $direct_supervisor_id = null;

        while (!$direct_supervisor_id){
            $parent_dep = $all_deps->where('ID', $parent_dep_id)->first();

            //на случай если у искомого пользователя нет руководителя
            if(!$parent_dep) return collect([]);

            $direct_supervisor_id = $parent_dep->HEAD;
            $parent_dep_id = $parent_dep->IBLOCK_SECTION_ID;
        }

        $direct_supervisor = User::findOrFail($direct_supervisor_id);
        return collect([$direct_supervisor]);
	}
}
