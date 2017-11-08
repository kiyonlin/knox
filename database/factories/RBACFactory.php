<?php

use App\Modules\Module\Module;
use Faker\Generator as Faker;

$factory->define(App\Modules\Role\Role::class, function (Faker $faker) {

    return [
        'name'         => $name =$faker->unique()->userName,
        'display_name' => $name,
        'description'  => $faker->sentence
    ];
});

$factory->define(App\Modules\Permission\Permission::class, function (Faker $faker) {

    return [
        'module_id'      => function () {
            return create(Module::class)->id;
        },
        'name'         => $faker->unique()->userName,
        'display_name' => null,
        'description'  => $faker->sentence
    ];
});
