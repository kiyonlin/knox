<?php

namespace App\Modules\Module;

use App\Http\Controllers\ApiController;
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
}
