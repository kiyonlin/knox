<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! user()->can('view_user')) {
            return $this->respondForbidden('对不起，您没有浏览用户权限!');
        }

        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(User::paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        return $this->respondCreated(
            User::create(request()->all())
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user)
    {
        if (! user()->can('update_user')) {
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
        if (! user()->can('delete_user')) {
            return $this->respondForbidden('对不起，您没有删除用户权限!');
        }

        $user->delete();

        return $this->respondNoContent();
    }
}
