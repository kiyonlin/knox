<?php

namespace App\Modules\Menu;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class MenusController extends ApiController
{
    /**
     * Display a listing of the menus.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! user()->can('view_menu')) {
            return $this->respondForbidden('对不起，您没有浏览菜单权限!');
        }

        $page = request('page', 1);
        $pageSize = request('pageSize', 10);

        return $this->respond(Menu::wherePid(0)->with('subMenus')->paginate($pageSize, ['*'], 'page', $page));
    }


    /**
     * Store a newly created menu in storage.
     *
     * @param CreateMenuRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMenuRequest $request)
    {
        return $this->respondCreated(
            Menu::create(request()->all())
        );
    }

    /**
     * Update the specified menu in storage.
     *
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Menu $menu)
    {
        if (! user()->can('update_menu')) {
            return $this->respondForbidden('对不起，您没有更新菜单的权限!');
        }

        $menu->update($this->validate(request(), [
            'pid' => 'sometimes|nullable|numeric',
            'name' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|nullable|string|max:255|unique:menus',
            'icon' => 'sometimes|nullable|string|max:255',
            'sort' => 'sometimes|nullable|numeric',
        ]));

        return $this->respondNoContent();
    }

    /**
     * Remove the specified menu from storage.
     *
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Menu $menu)
    {
        if (! user()->can('delete_menu')) {
            return $this->respondForbidden('对不起，您没有删除菜单权限!');
        }

        $menu->delete();

        return $this->respondNoContent();
    }
}
