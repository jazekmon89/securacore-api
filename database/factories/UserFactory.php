<?php

use Faker\Generator as Faker;
use App\User;
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

$factory->define(User::class, function (Faker $faker) {

    return [
        'first_name' => $faker->firstNameMale,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'status' => $faker->numberBetween($min = 0, $max = 1),  // inactive - 0, active - 1
        'role' => $faker->numberBetween($min = 1, $max = 2),
        'password' => 'secretsecret',
        'password_confirmation' => 'secretsecret',
        'random_token' => $faker->asciify('********************'),
        'email_verified_at' => now(),
    ];
});