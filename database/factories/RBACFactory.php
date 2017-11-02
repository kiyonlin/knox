<?php

use Faker\Generator as Faker;

$factory->define(App\Modules\Role\Role::class, function (Faker $faker) {

    return [
        'name'         => $faker->unique()->userName,
        'display_name' => $faker->userName,
        'description'  => $faker->sentence
    ];
});

$factory->define(App\Modules\Permission\Permission::class, function (Faker $faker) {

    return [
        'name'         => $faker->unique()->userName,
        'display_name' => $faker->userName,
        'description'  => $faker->sentence
    ];
});
