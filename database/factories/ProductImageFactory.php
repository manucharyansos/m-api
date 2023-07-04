<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
//        return [
//            'image_path' => $this->faker->imageUrl(),
//            'product_id' => Product::factory(),
//        ];
    }
}
