<?php

/** @var Factory $factory */
use App\Models\UserWallet;
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

$factory->define(UserWallet::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'currency_id' => 1,
        'balance' => 50
    ];
});
