<?php

use Faker\Generator as Faker;
use App\Website;
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

$factory->define(Website::class, function (Faker $faker) {

    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'url' => $faker->unique()->url,
        'public_key' => $faker->unique()->randomNumber($nbDigits = 6, $strict = false),
        'is_activated' => $faker->numberBetween($min = 0, $max = 1),
        'notes' => $faker->sentence,
        'online' => $faker->numberBetween($min = 0, $max = 1),
        'is_scanned' => $faker->numberBetween($min = 0, $max = 1)
    ];
});