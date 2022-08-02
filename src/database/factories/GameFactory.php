<?php

/** @var Factory $factory */
use App\Models\Game;
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

$factory->define(Game::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'jackpot_value' => 10000,
        'admin_fee_percentage' => 30,
        'pot_value' => 50000,
        'entry_fee' => 1,
        'number_of_winners' => '10',
        'game_ext_days' => 4,
        'is_extended' => 1,
        'amount_of_balls_to_fire' => 4,
        "total_attempts" => 3,
        "start_date" => Carbon::today()->subDays(2),
        "end_date" => Carbon::today(),
    ];
});
