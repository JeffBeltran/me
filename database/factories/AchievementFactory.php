<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Achievement;
use Faker\Generator as Faker;

$factory->define(Achievement::class, function (Faker $faker) {
    return [
        'blurb' => $faker->paragraph(),
        'job_id' => 42
    ];
});
