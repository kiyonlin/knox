<?php

namespace App\Modules\Role;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class RolePermissionsController extends ApiController
{

    /**
     * Display a listing of the role's module permission tree.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        return $this->respond($role->modulePermissionTree());
    }

    public function update(Role $role)
    {
        if (Role::SYSTEM_ADMIN == $role->name) {
            return $this->respondForbidden();
        }

        $checkedPermissions = request('checkedKeys');

        $role->savePermissions($checkedPermissions);

        return $this->respondNoContent();
    }
}
