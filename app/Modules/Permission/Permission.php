<?php

namespace App\Modules\Permission;

use Parsidev\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $fillable = ['module_id', 'name', 'display_name', 'description'];
}