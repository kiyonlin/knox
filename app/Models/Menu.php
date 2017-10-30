<?php

namespace App\Models;

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

    /**
     * 维护层级和索引
     *
     * @param $menu
     */
    private static function maintainLevelAndIndex($menu)
    {
        if (! empty($menu->pid)) {
            $parentMenu = self::find($menu->pid);
            $menu->index = $parentMenu->index . '-' . $menu->id;
            $menu->level = $parentMenu->level + 1;
        } else {
            $menu->index = $menu->id;
        }
        $menu->save();
    }
}
