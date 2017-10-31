<?php
if (! function_exists('buildMenuTree')) {
    function buildMenuTree(array $menus, $pid = 0)
    {
        $submenu = [];

        foreach ($menus as $index => $menu) {
            if ($menu['pid'] == $pid) {
                $menu['submenu'] = buildMenuTree($menus, $menu['id']);
                $submenu[] = $menu;
                unset($menus[$index]);
            }
        }

        return $submenu;
    }
}

if (! function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (! function_exists('uid')) {
    function uid()
    {
        return auth()->id();
    }
}