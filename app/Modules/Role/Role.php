<?php

namespace App\Modules\Role;

use App\Modules\Module\Module;
use Illuminate\Support\Collection;
use Parsidev\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * 获取当前角色的模块权限树
     *
     * @return Collection
     */
    public function modulePermissionTree()
    {
        $permIds = $this->perms->pluck('id')->toArray();

        return Module::wherePid(0)->with('subModules')->get()->transform(function (Module $module) use ($permIds) {
            if ($module->is_leaf) {
                foreach ($module->perms as $perm) {
                    $perm->selected = in_array($perm->id, $permIds);
                }
            } else {
                foreach ($module->subModules as $subModule) {
                    foreach ($subModule->perms as $perm) {
                        $perm->selected = in_array($perm->id, $permIds);
                    }
                }
            }

            return $module;
        });
    }
}