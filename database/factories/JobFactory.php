<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'company_id' => 42,
        'title' => $faker->jobTitle(),
        'blurb' => $faker->paragraph(),
        'state' => $faker->stateAbbr(),
        'city' => $faker->city(),
        'start' => $faker->dateTimeBetween('-5 year', '-1 year'),
        'end' => $faker->optional(0.2)->dateTimeBetween('-1 year', '-1 day')
    ];
});
