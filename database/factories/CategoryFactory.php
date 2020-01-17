<?php

use Faker\Generator as Faker;

$factory->define(CodeShopping\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName
    ];
});
