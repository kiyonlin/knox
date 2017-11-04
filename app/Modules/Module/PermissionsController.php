<?php

namespace App\Modules\Module;

use App\Http\Controllers\ApiController;
use App\Modules\Permission\Permission;
use Illuminate\Http\Request;

class PermissionsController extends ApiController
{

    /**
     * Store a newly created permission in storage.
     *
     * @param Module $module
     * @param CreatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Module $module, CreatePermissionRequest $request)
    {
        return $this->respondCreated(
            $module->addPerm($request->all())
        );
    }

    /**
     * Update the specified permission in storage.
     *
     * @param Module $module
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Module $module, Permission $permission)
    {
        $permission->update($this->validate(request(), [
            'display_name' => 'sometimes|string|max:255',
            'description'  => 'sometimes|string|max:511',
        ]));

        return $this->respondNoContent();
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param Module $module
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Module $module, Permission $permission)
    {
        $permission->delete();

        return $this->respondNoContent();
    }
}
