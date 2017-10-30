<?php

namespace App\Models\RBAC;

use Parsidev\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $fillable = ['name', 'display_name', 'description'];
}