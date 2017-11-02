<?php

namespace App\Modules\Permission;

use Parsidev\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $fillable = ['name', 'display_name', 'description'];
}