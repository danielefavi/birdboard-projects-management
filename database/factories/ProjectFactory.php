<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'description' => $faker->sentence(4),
        'notes' => $faker->paragraph,
        'owner_id' => function() {
            return factory(\App\User::class)->create()->id;
        }
    ];
});
