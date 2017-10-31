<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Menu::class, function (Faker $faker) {
    return [
        'pid'   => 0,
        'key'   => Str::random(5),
        'name'  => $faker->userName,
        'path'  => null,
        'level' => 1,
        'index' => '',
        'icon'  => null
    ];
});
