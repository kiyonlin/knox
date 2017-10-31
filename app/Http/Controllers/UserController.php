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
        $pageSize = request('pageSize', 15);

        return $this->respond(User::paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @internal param User $user
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
