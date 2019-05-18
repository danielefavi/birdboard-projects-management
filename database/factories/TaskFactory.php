<?php

use Faker\Generator as Faker;
use App\Task;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'completed' => false,
        'project_id' => factory(\App\Project::class)

        // the line before does the same thing of the code below
        // 'project_id' => function() {
        //     return factory(\App\Project::class)->create()-id
        // }
    ];
});
