<?php

namespace App\Modules\Permission;

use Parsidev\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $fillable = ['module_id', 'name', 'display_name', 'description'];

    /**
     * 没有填写显示名称时，使用名称填充
     *
     * @param $value
     */
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = $value ? : $this->attributes['name'];
    }
}