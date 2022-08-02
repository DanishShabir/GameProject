<?php

/** @var Factory $factory */
use App\Models\GameExtension;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factory;

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

$factory->define(GameExtension::class, function (Faker $faker) {
    return [
        'game_id' => 1,
        'extension_days' => 3,
        'start_date' => Carbon::today(),
        'end_date' => Carbon::today(),
    ];
});
