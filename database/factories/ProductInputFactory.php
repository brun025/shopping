<?php

use Faker\Generator as Faker;
use CodeShopping\ProductInput;

$factory->define(ProductInput::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(1, 20)
    ];
});
