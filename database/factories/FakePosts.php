<?php

use Components\Model\Posts;

$factory->define(Posts::class, function (Faker\Generator $faker) {
 
     
    $title = $faker->sentence;
    $slug = $faker->slug;

    return [
        'title'          => $title,
        'type'           => 'post',
        'slug'           =>  $slug,
        'user_id'       => 1
     ];

});
