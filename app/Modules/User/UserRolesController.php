<?php

namespace App\Modules\User;

use App\Http\Controllers\ApiController;
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
        return $this->respond($user->optionalRoles());
    }

    /**
     * Update the specified user in storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user)
    {
        if (! user()->can('user.update')) {
            return $this->respondForbidden('对不起，您没有更新用户权限!');
        }

        $validate = $this->validate(request(), [
            'display_name' => 'sometimes|string|max:255',
            'email'        => 'sometimes|required|string|email|max:255|unique:users',
            'password'     => 'sometimes|required|string|min:6',
        ]);

        $user->update($validate);

        return $this->respondNoContent();
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(User $user)
    {
        if (! user()->can('user.delete')) {
            return $this->respondForbidden('对不起，您没有删除用户权限!');
        }

        $user->delete();

        return $this->respondNoContent();
    }
}
