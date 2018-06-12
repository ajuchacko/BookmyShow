<?php

use Faker\Generator as Faker;

$factory->define(App\Ticket::class, function (Faker $faker) {
    return [
        'movie_id' => function() {
          return factory(App\Movie::class)->create()->id;
        },
    ];
});

$factory->state(App\Ticket::class, 'reserved', function (Faker $faker) {
    return [
        'reserved_at' => Carbon\Carbon::now()
    ];
});
