<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> 'adobong baboy',
            'description' => 'this dish originated from cebu',
            "ingredients" => '2 tbsp canola oil
            6 cloves garlic crushed
            1 pc onion, sliced
            1 kilogram chicken cut ups
            2 tbsp vinegar
            1/4 cup soy sauce
            1 cup water
            2 pcs bay leaves
            1 tsp whole black peppercorns, slightly crushed
            2 pc Knorr chicken cubes
            1 tsp brown sugar packed
            Option: 1 cup kale or spinach',
            'procedures' => ' 1
            Heat oil in pan and sauté garlic and onions. Then add chicken to the pan and sear on all sides, until you have a little browning in the chicken skin.
             2
            Pour in vinegar, soy sauce and water. Add bay leaves, pepper and Knorr Chicken Cubes. Bring to a boil over high heat then reduce heat to simmer, but do not cover the pan. Continue to simmer for 10 mins.
             3
            Remove chicken pieces from sauce and fry in another pan until nicely browned.
             4
            Put back fried chicken pieces into sauce. Add sugar and let simmer again for another 10 minutes or until sauce has thickened. Serve warm.',

            'tag' => ' baboy',
            'category' => 'dinner',
            'video_url' => '',
            'img_url' => '',
            'user_id' => User::first(),
            'status' => 'free',
        ];

    }
}
