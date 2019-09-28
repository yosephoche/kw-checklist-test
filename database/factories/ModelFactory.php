<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => Hash::make('admin'),
    ];
});

$factory->define(App\Checklist::class, function (Faker\Generator $faker) {
    return [
        'object_domain' => 'Deals',
        'object_id' => 1,
        'description' => 'I Got new Deals',
        'is_completed' => true,
        'urgency' => 0,
        'task_id' => '123',
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'description' => 'Deals Item',
        'due' => Carbon::now(),
        'urgency' => 0,
        'assignee_id' => 1234,
        'checklist_id' => 1,
        'user_id' => 1,
    ];
});
