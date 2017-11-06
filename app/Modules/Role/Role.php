<?php

namespace App\Modules\Role;

use App\Modules\Module\Module;
use Illuminate\Support\Collection;
use Parsidev\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * 没有填写显示名称时，使用名称填充
     *
     * @param $value
     */
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = $value ? : $this->attributes['name'];
    }

    /**
     * 获取当前角色的模块权限树
     *
     * @return Collection
     */
    public function modulePermissionTree()
    {
        $permIds = $this->perms->pluck('id')->toArray();
        $defaultCheckedKeys = [];

        $tree = Module::wherePid(0)->with('subModules')->get()->transform(function (Module $module) use ($permIds, &$defaultCheckedKeys) {
            $node = [];
            if ($module->is_leaf) {
                foreach ($module->perms as $perm) {
                    $node['children'][] = [
                        'id'    => $perm->id,
                        'key'   => $perm->id,
                        'label' => $perm->display_name,
                        'leaf'  => true,
                    ];
                    if (in_array($perm->id, $permIds)) {
                        $defaultCheckedKeys[] = $perm->id;
                    }
                }
                $node['key'] = $module->key;
                $node['label'] = $module->name;
                $node['leaf'] = false;
            } else {
                foreach ($module->subModules as $subModule) {
                    $child = [];
                    foreach ($subModule->perms as &$perm) {
                        $child['children'][] = [
                            'id'    => $perm->id,
                            'key'   => $perm->id,
                            'label' => $perm->display_name,
                            'leaf'  => true,
                        ];
                        if (in_array($perm->id, $permIds)) {
                            $defaultCheckedKeys[] = $perm->id;
                        }
                    }
                    $child['key'] = $subModule->key;
                    $child['label'] = $subModule->name;
                    $child['leaf'] = false;
                    $node['children'][] = $child;
                }
                $node['key'] = $module->key;
                $node['label'] = $module->name;
                $node['leaf'] = false;
            }

            return $node;
        })->toArray();

        return compact('tree', 'defaultCheckedKeys');
    }
}