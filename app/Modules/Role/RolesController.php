<?php

namespace App\Modules\Role;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class RolesController extends ApiController
{

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(Role::paginate($pageSize, ['*'], 'page', $page));
    }


    /**
     * Store a newly created role in storage.
     *
     * @param CreateRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        return $this->respondCreated(
            Role::create(request()->all())
        );
    }

    /**
     * Update the specified role in storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Role $role)
    {
        $role->update($this->validate(request(), [
            'display_name' => 'sometimes|string|max:255',
            'description'  => 'sometimes|string|max:511',
        ]));

        return $this->respondNoContent();
    }

    /**
     * Remove the specified role from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->respondNoContent();
    }


}
