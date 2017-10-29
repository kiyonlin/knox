<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Menu::class, function (Faker $faker) {
    return [
        'pid'   => null,
        'key'   => Str::random(5),
        'name'  => $faker->userName,
        'level' => 1,
        'index' => '',
        'icon'  => null
    ];
});
