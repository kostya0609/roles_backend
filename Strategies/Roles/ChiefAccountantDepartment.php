<?php

namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Models\Department;
use App\Modules\Roles\Models\User;

use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ChiefAccountantDepartment implements RoleInterface
{

    public function execute(int $user_id, array $params = []): Collection
    {
        $nrt_id     = 1528; //НРТ - Направление розничной торговли
        $not_id     = 1373; //НОТ - Направление оптовой торговли
        $sn_id      = 1573; //СН - Сервисное направление
        $buh_not_id = 1359; //БУХ НОТ - Бухгалтерия направления оптовой торговли
        $buh_nrt_id = 1363; //БУХ НРТ - Бухгалтерия направления розничной торговли
        $buh_sn_and_u_id = 1360; //БУХ СНиУ - Бухгалтерия сервисного направления и услуг

        $branch_not = $this->departmentsIdHierarchy($not_id);//ветка НОТ
        $branch_sn  = $this->departmentsIdHierarchy($sn_id); //ветка СН
        $branch_nrt = $this->departmentsIdHierarchy($nrt_id);//ветка НРТ

        $branch_nrt_except_sn = array_diff($branch_nrt, $branch_sn);//ветка НРТ кроме СН с иерархией

        $user_dep = $this->userDepartment($user_id);
        if(in_array($user_dep['ID'],$branch_not))
        {
            $buh_not = $this->allDepartments()->firstWhere('ID','=',$buh_not_id);
            $head_id_buh_not = $buh_not->HEAD;
            return collect([User::find($head_id_buh_not)]);
        }
        elseif(in_array($user_dep['ID'],$branch_nrt_except_sn))
        {
            $buh_nrt = $this->allDepartments()->firstWhere('ID','=',$buh_nrt_id);
            $head_id_buh_nrt = $buh_nrt->HEAD;
            return collect([User::find($head_id_buh_nrt)]);
        }
        else
        {
            $buh_sn_and_u = $this->allDepartments()->firstWhere('ID','=',$buh_sn_and_u_id);
            $head_id_buh_sn_and_u = $buh_sn_and_u->HEAD;
            return collect([User::find($head_id_buh_sn_and_u)]);
        }
    }

    private function departmentsIdHierarchy($dep_id): array
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
}
