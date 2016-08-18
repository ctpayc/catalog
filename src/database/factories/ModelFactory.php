<?php

use Faker\Factory as F;

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

// $factory->define(App\User::class, function (Faker\Generator $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->email,
//         'password' => bcrypt(str_random(10)),
//         'remember_token' => str_random(10),
//     ];
// });

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $faker = F::create('ru_RU');
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    $faker = F::create('ru_RU');
    $price = $faker->numberBetween($min = 1000000, $max = 9000000);
    $rooms = $faker->numberBetween($min = 1, $max = 5);
    $total_space = $faker->numberBetween($min = 39, $max = 120);
    $floor = $faker->numberBetween($min = 1, $max = 24);
    $total_floor = $faker->numberBetween($min = $floor, $max = 25);
    $ad_category = $faker->randomDigitNotNull;
    $deal_type = $faker->randomElement($array = array ('buy','rent','sell', 'take', 'change'));
    $coordinates = (55 + round((float)rand()/(float)getrandmax(), 6)) . "," . (86 + round((float)rand()/(float)getrandmax(), 6));

    if (rand(1,3) > 1 && $deal_type == 'change') {
        $square_total_min = $faker->numberBetween($min = 39, $max = 120);
        $square_total_max = $square_total_min + rand(20,50);
        if (rand(1,2) > 1) {
            $wishlist = json_decode('{
                "4" : {
                    "rooms" : '.json_encode($faker->randomElements(["1", "2", "3", "4", "5", ">5"], $count = rand(1, 4))).',
                    "flat-type" : '.json_encode($faker->randomElements(["Хрущевка", "Типовая", "Улучшенной планировки", "Студия"], $count = rand(1, 4))).',
                    "price_max" : "'.$faker->numberBetween($min = 2000000, $max = 4000000).'",
                    "square-total_min" : "'.$square_total_min.'",
                    "square-total_max" : "'.$square_total_max.'",
                    "square-kitchen_min" : "'.$faker->numberBetween($min = 10, $max = 20).'",
                    "floor-max" : "'.$faker->numberBetween($min = 5, $max = 20).'"
                  },
                  "5" : {
                    "rooms" : '.json_encode($faker->randomElements(["1", "2", "3", "4", "5", ">5"], $count = rand(1, 4))).',
                    "price_max" : "'.$faker->numberBetween($min = 2000000, $max = 4000000).'",
                    "house-type" : '.json_encode($faker->randomElements(["Кирпич", "Кирпич на каркасе", "Бетон", "Монолит", "Брус", "Цилиндр. пенобетон", "Газобетон", "Арболит", "Сибит", "Шлакоблоки", "Другое"], $count = rand(1, 4))).',
                    "square-total_min" :"'.$square_total_min.'",
                    "square-total_max" :"'.$square_total_max.'",
                    "floor-max" : "'.$faker->numberBetween($min = 5, $max = 20).'"
                  }
            }',JSON_UNESCAPED_SLASHES);
        } else {
            $wishlist = json_decode('{
                "4" : {
                    "rooms" : '.json_encode($faker->randomElements(["1", "2", "3", "4", "5", ">5"], $count = rand(1, 4))).',
                    "flat-type" : '.json_encode($faker->randomElements(["Хрущевка", "Типовая", "Улучшенной планировки", "Студия"], $count = rand(1, 4))).',
                    "price_max" : "'.$faker->numberBetween($min = 2000000, $max = 4000000).'",
                    "house-type" : '.json_encode($faker->randomElements(["Кирпич", "Кирпич на каркасе", "Бетон", "Монолит", "Брус", "Цилиндр. пенобетон", "Газобетон", "Арболит", "Сибит", "Шлакоблоки", "Другое"], $count = rand(1, 4))).',
                    "square-total_min" : "'.$square_total_min.'",
                    "square-kitchen_min" : "'.$faker->numberBetween($min = 10, $max = 20).'",
                    "floor-max" : "'.$faker->numberBetween($min = 5, $max = 20).'"
                  },
                  "5" : {
                    "rooms" : '.json_encode($faker->randomElements(["1", "2", "3", "4", "5", ">5"], $count = rand(1, 4))).',
                    "price_max" : "'.$faker->numberBetween($min = 2000000, $max = 4000000).'",
                    "house-type" : '.json_encode($faker->randomElements(["Кирпич", "Кирпич на каркасе", "Бетон", "Монолит", "Брус", "Цилиндр. пенобетон", "Газобетон", "Арболит", "Сибит", "Шлакоблоки", "Другое"], $count = rand(1, 4))).',
                    "square-total_max" :"'.$square_total_max.'",
                    "floor-max" : "'.$faker->numberBetween($min = 5, $max = 20).'"
                  }
            }',JSON_UNESCAPED_SLASHES);
        }
        
    } else {
        $wishlist = null;
    }

    return [
        'name' => $rooms . '-комнатная ' . $total_space . ' м², этаж ' . $floor . ' из ' . $total_floor,
        'price' => $price,
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'root_catalog' => 1,
        'ad_category' => $ad_category,
        'deal_type' => $deal_type,
        'views' => $faker->numberBetween($min = 100, $max = 15000),
        'params' => json_decode('{
                "_2": "'.$rooms.'",
                "_3": "'.$total_space.'",
                "_7": "'.$faker->randomElement($array = ["Кирпич", "Кирпич на каркасе", "Бетон", "Монолит", "Брус", "Цилиндр. пенобетон", "Газобетон", "Арболит", "Сибит", "Шлакоблоки", "Другое"]).'",
                "_9": "'.$faker->randomElement($array = ["Хрущевка", "Типовая", "Улучшенной планировки", "Студия", "Другое"]).'",
                "_11": "'.$floor.'",
                "_12": "'.$total_floor.'",
                "_13": "'.$price.'",
                "_15": "'.$faker->randomElement($array = ["Частная", "ДДУ", "Инвестиционный договор", "Кооператив.", "Другое"]).'",
                "photos": [],
                "catalog": "'.$ad_category.'",
                "city_sel": "'.$faker->city.'",
                "deal_type": "'.$deal_type.'",
                "coordinates": "'.$coordinates.'",
                "description": "'.$faker->address.'",
                "crazy_address": "'.$faker->address.'"}',
                JSON_UNESCAPED_SLASHES),
        'wishlist' => $wishlist
    ];
});