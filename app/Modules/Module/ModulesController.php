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
        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(Module::wherePid(0)
            ->orderBy('sort')
            ->with('subModules')
            ->paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Display a listing of the top modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function tops()
    {
        return $this->respond(
            Module::wherePid(0)
                ->where('key', '<>', Module::SYSTEM_MODULE)
                ->orderBy('sort, created')
                ->get(['id', 'name'])
        );
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
            Module::create($request->all())->load('subModules')
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
        $module->update($this->validate(request(), [
            'pid'  => 'sometimes|nullable|numeric',
            'name' => 'sometimes|required|string|max:255',
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
        $module->delete();

        return $this->respondNoContent();
    }
}
