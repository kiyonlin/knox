<?php
if (! function_exists('buildModuleTree')) {
    function buildModuleTree(array $modules, $pid = 0)
    {
        $submodules = [];

        foreach ($modules as $index => $module) {
            if ($module['pid'] == $pid) {
                $module['submodules'] = buildModuleTree($modules, $module['id']);
                $submodules[] = $module;
                unset($modules[$index]);
            }
        }

        return $submodules;
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