<?php

namespace App\Modules\Role;

use Parsidev\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'display_name', 'description'];
}