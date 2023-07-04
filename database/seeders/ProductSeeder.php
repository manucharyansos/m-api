<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsCount = 50;
        $imagesPerProductRange = [3, 5];

        $subcategoryIds = Subcategory::pluck('id');

        $imagePaths = ['image1.jpg', 'image2.png', 'image3.png', 'image4.png', 'image5.png'];

        for ($i = 1; $i <= $productsCount; $i++) {
            $product = Product::create([
                'title' => 'Product ' . $i,
                'description' => 'This is product ' . $i,
                'price' => rand(10, 100),
                'stock' => rand(1, 10),
                'subcategory_id' => $subcategoryIds->random(),
            ]);

            $imagesCount = rand($imagesPerProductRange[0], $imagesPerProductRange[1]);

            for ($j = 1; $j <= $imagesCount; $j++) {
                $imagePath = $imagePaths[array_rand($imagePaths)];
                $imageContent = file_get_contents(public_path('products-images/' . $imagePath));
                $imageName = time() . '-' . $imagePath;

                Storage::disk('public')->put('products-images/' . $imageName, $imageContent);

                $productImage = new ProductImage([
                    'image_path' =>  $imageName,
                ]);

                $product->images()->save($productImage);
            }
        }
    }
}
