<?php

namespace App\Modules\User;

use App\Http\Controllers\ApiController;
use App\Modules\Role\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserRolesController extends ApiController
{

    /**
     * Display a listing of the roles that do not attached to the given user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return $this->respond($user->optionalRoles(request('query', '')));
    }

    /**
     * Update the specified user in storage.
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user, Role $role)
    {
        $user->attachRole($role);

        return $this->respondNoContent();
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(User $user, Role $role)
    {

        $user->detachRole($role);

        return $this->respondNoContent();
    }
}
