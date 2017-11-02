<?php

namespace App\Modules\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    public static function boot()
    {
        self::created(function ($menu) {
            self::maintainLevelAndIndex($menu);
        });
    }

    protected $guarded = [];

    protected $casts = [
        'is_leaf' => 'boolean'
    ];

    /*
     * 前端可见字段
     */
    protected $visible = ['id', 'pid', 'key', 'name', 'path', 'index', 'level', 'icon', 'is_leaf'];

    /**
     * 维护层级和索引
     *
     * @param $menu
     */
    private static function maintainLevelAndIndex($menu)
    {
        if ($menu->pid) {
            $parentMenu = self::find($menu->pid);
            $menu->index = $parentMenu->index . '-' . $menu->id;
            $menu->level = $parentMenu->level + 1;

            $parentMenu->is_leaf = false;
            $parentMenu->save();
        } else {
            $menu->index = $menu->id;
        }
        $menu->save();
    }
}
