<?php
use App\Order;
use Carbon\Carbon;
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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Concert::class, function (Faker\Generator $faker) {
    return [
        'title' => 'Example Jenny\'s Tang',
        'subtitle' => 'With Animosity and Theaberton',
        'date' => Carbon::parse('+2 weeks'),
        'ticket_price' => 2000,
        'venue' => 'The Example Pit',
        'venue_address' => '123 Example Lane',
        'city' => 'Fakeville',
        'state' => 'ON',
        'zip' => '90210',
        'additional_information' => 'Some simple inforation',
    ];
});

$factory->state(App\Concert::class, 'published', function ($faker) {
   return [
       'published_at' => Carbon::parse('-1 week')
   ];
});

$factory->state(App\Concert::class, 'unpublished', function ($faker) {
    return [
        'published_at' => null
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'concert_id' => function() {
        return factory(App\Concert::class)->create()->id;
        }
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'amount' => '2050',
        'email' => 'jtang@gmail.com'
    ];
});

$factory->state(App\Ticket::class, 'reserved_at', function ($faker) {
    return [
        'reserved_at' => Carbon::now()
    ];
});
