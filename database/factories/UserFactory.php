<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Modules\User\User::class, function (Faker $faker) {
    static $password;

    return [
        'phone_number'   => $faker->unique()->phoneNumber,
        'username'       => $faker->name,
        'display_name'   => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('111111'),
        'remember_token' => str_random(10),
    ];
});
