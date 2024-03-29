<?php

namespace Database\Factories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
//    public function definition()
//    {
//        return [
//            'name' => $this->faker->unique()->word . $this->faker->unique()->randomNumber(),
//            'description' => $this->faker->sentence,
//            'image' => 'woman-wallet6.jpg',
//            'category_id' => function () {
//                return \App\Models\Category::factory()->create()->id;
//            },
//        ];
//    }

    public function definition()
    {
        static $subcategoryNumber = 1;

        $name = 'Subcategory' . $subcategoryNumber;
        $description = 'This is subcategory description ' . $subcategoryNumber;
        $image = 'images' . $subcategoryNumber . '.jpg';

        $subcategoryNumber++;

        return [
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'category_id' => function () {
                return \App\Models\Category::factory()->create()->id;
                    }
        ];
    }
}
