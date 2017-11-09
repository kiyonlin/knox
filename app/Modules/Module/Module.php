<?php

namespace App\Modules\Module;

use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    const SYSTEM_MODULE = 'system';

    public static function boot()
    {
        self::created(function ($module) {
            self::maintainLevelAndIndex($module);
            self::createPermission($module);
        });

        self::deleted(function ($module) {
            $module->perms()->delete();
        });
    }

    protected $guarded = [];

    protected $with = ['perms'];

    protected $casts = [
        'is_leaf' => 'boolean',
        'pid'     => 'integer',
        'level'   => 'integer',
        'index'   => 'string'
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

    /**
     * 父模块
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentModule()
    {
        return $this->belongsTo(Module::class, 'pid');
    }

    /**
     * 权限关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perms()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * 增加单个权限
     *
     * @param $permission
     * @return false|Model
     */
    public function addPerm($permission)
    {
        if (is_array($permission)) {
            $permission = new Permission($permission);
        }

        return $this->perms()->save($permission);
    }

    /**
     * 增加多个权限
     *
     * @param $permissions
     */
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
            $module->is_leaf = ! optional($module->subModules())->count();
        }
        $module->save();
    }

    /**
     * 创建了模块后，需要为这个模块创建一条单独查看权限
     * 并且将权限自动加到系统管理员角色上
     *
     * @param $module
     */
    private static function createPermission($module)
    {
        $permission = $module->addPerm([
            'name'         => "module.view.{$module->key}",
            'display_name' => $display_name = "查看{$module->name}模块",
            'description'  => $display_name
        ]);

        Role::whereName(Role::SYSTEM_ADMIN)->first()->attachPermission($permission);
    }
}
