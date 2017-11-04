<?php

use Illuminate\Support\Debug\Dumper;

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

if (! function_exists('dp')) {
    /**
     * Dump the passed variables and control weather end the script or not.
     *
     * @param $args
     * @param bool $endScript
     * @return void
     * @internal param $mixed
     */
    function dp($args, $endScript = true)
    {
        (new Dumper)->dump($args);

        if($endScript) die(1);
    }
}