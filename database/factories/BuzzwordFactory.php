<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Buzzword;
use Faker\Generator as Faker;

$factory->define(Buzzword::class, function (Faker $faker) {
    return [
        'word' => $faker->bs(),
        'details' => $faker->paragraph()
    ];
});
