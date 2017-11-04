<?php

namespace App\Modules\Module;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class ModulesController extends ApiController
{
    /**
     * Display a listing of the modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! user()->can('module.view')) {
            return $this->respondForbidden('对不起，您没有浏览模块权限!');
        }

        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(Module::wherePid(0)
            ->orderBy('sort, created')
            ->with('subModules')
            ->paginate($pageSize, ['*'], 'page', $page));
    }


    /**
     * Store a newly created module in storage.
     *
     * @param CreateModuleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateModuleRequest $request)
    {
        return $this->respondCreated(
            Module::create($request->all())
        );
    }

    /**
     * Update the specified module in storage.
     *
     * @param Module $module
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Module $module)
    {
        if (! user()->can('module.update')) {
            return $this->respondForbidden('对不起，您没有更新模块的权限!');
        }

        $module->update($this->validate(request(), [
            'pid' => 'sometimes|nullable|numeric',
            'name' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|nullable|string|max:255|unique:modules',
            'icon' => 'sometimes|nullable|string|max:255',
            'sort' => 'sometimes|nullable|numeric',
        ]));

        return $this->respondNoContent();
    }

    /**
     * Remove the specified module from storage.
     *
     * @param Module $module
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Module $module)
    {
        if (! user()->can('module.delete')) {
            return $this->respondForbidden('对不起，您没有删除模块权限!');
        }

        $module->delete();

        return $this->respondNoContent();
    }
}
