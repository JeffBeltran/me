<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\School;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'level' => $faker->word(),
        'category' => $faker->word(),
        'specialty' => $faker->word(),
        'graduated' => $faker->dateTimeThisDecade()
    ];
});
