<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Movie::class, function (Faker $faker) {
    return [
      'name' => $faker->words($nb = 4, $asText = true),
      'image' => 'images/exampleFilm.jpg',
      'trailer' => 'https://www.youtube.com/watch?v=uDKdkfj89u0',
      'language' => 'Russian',
      'genre' => 'Action',
      'ticket_price' => 2000,
      'release_date' => Carbon::parse('+2 weeks'),
      'duration' => '8720',
      'summary' => 'Summary for the testing example Film.'
    ];
});

$factory->state(App\Movie::class, 'published', function (Faker $faker) {
    return [
      'published_at' => Carbon::parse('-1 week'),
    ];
});

$factory->state(App\Movie::class, 'unpublished', function (Faker $faker) {
    return [
      'published_at' => null,
    ];
});
