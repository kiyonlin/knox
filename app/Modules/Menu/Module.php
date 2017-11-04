<?php

namespace App\Modules\Module;

use App\Modules\Permission\Permission;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    public static function boot()
    {
        self::created(function ($module) {
            self::maintainLevelAndIndex($module);
        });
    }

    protected $guarded = [];

    protected $casts = [
        'is_leaf' => 'boolean',
        'pid'     => 'integer'
    ];

    /**
     * 子模块关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subModules()
    {
        return $this->hasMany(Module::class, 'pid')->orderBy('sort, created');
    }

    public function perms()
    {
        return $this->hasMany(Permission::class);
    }

    public function addPerm($permission)
    {
        if (is_array($permission)) {
            $permission = new Permission($permission);
        }

        return $this->perms()->save($permission);
    }

    public function addPerms($permissions)
    {
        foreach ($permissions as $permission) {
            $this->addPerm(new Permission($permission));
        }
    }

    /**
     * 维护层级和索引
     *
     * @param $module
     */
    private static function maintainLevelAndIndex($module)
    {
        if ($module->pid) {
            $parentModule = self::find($module->pid);
            $module->index = $parentModule->index . '-' . $module->id;
            $module->level = $parentModule->level + 1;

            $parentModule->is_leaf = false;
            $parentModule->save();
        } else {
            $module->index = $module->id;
            $module->level = 1;
            $module->is_leaf = count(optional($module->subModules())->count());
        }
        $module->save();
    }
}
