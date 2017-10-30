<?php

use Faker\Generator as Faker;

$factory->define(App\Models\RBAC\Role::class, function (Faker $faker) {

    return [
        'name'         => $faker->unique()->userName,
        'display_name' => $faker->userName,
        'description'  => $faker->sentence
    ];
});

$factory->define(App\Models\RBAC\Permission::class, function (Faker $faker) {

    return [
        'name'         => $faker->unique()->userName,
        'display_name' => $faker->userName,
        'description'  => $faker->sentence
    ];
});
