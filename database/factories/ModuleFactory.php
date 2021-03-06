<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Modules\Module\Module::class, function (Faker $faker) {
    return [
        'pid'     => 0,
        'key'     => $faker->unique()->md5,
        'name'    => $faker->userName,
        'path'    => '-',
        'level'   => 1,
        'index'   => '',
        'icon'    => null,
        'is_leaf' => true,
        'sort'    => null
    ];
});
