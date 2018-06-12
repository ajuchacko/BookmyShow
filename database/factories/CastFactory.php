<?php

use Faker\Generator as Faker;

$factory->define(App\Cast::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->firstName,
        'male' => $faker->boolean,
        'female' => false,
        'image' => $faker->imageUrl(),
        'birth' => $faker->dateTime(),
        'details' => $faker->realText()
    ];
});
