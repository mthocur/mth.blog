<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        "name"=>$faker->name,
        "slug"=>strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $faker->name))),
        "category_id"=>null
    ];
});
