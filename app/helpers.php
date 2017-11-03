<?php
if (! function_exists('buildMenuTree')) {
    function buildMenuTree(array $menus, $pid = 0)
    {
        $submenus = [];

        foreach ($menus as $index => $menu) {
            if ($menu['pid'] == $pid) {
                $menu['submenus'] = buildMenuTree($menus, $menu['id']);
                $submenus[] = $menu;
                unset($menus[$index]);
            }
        }

        return $submenus;
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