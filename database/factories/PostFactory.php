<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        "user_id"=>factory("App\User")->create()->id,
        "title"=>$faker->name,
        "slug"=>strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $faker->name))),
        "content"=>$faker->text,
        "summary"=>$faker->text
    ];
});