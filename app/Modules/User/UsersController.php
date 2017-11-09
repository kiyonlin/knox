<?php

namespace App\Modules\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsersController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(User::with('roles')->paginate($pageSize, ['*'], 'page', $page));
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
     * Update the specified user in storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user)
    {
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
        $user->delete();

        return $this->respondNoContent();
    }
}
