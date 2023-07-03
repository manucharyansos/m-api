<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ProductImageSeeder extends Seeder
{

    public function run()
    {
//        $products = Product::all();
//
//        foreach ($products as $product) {
//            $images = ProductImage::factory()->count(5)->create([
//                'product_id' => $product->id,
//            ]);
//
//            foreach ($images as $image) {
//                if ($image instanceof UploadedFile) {
//                    $imageName = time() . '-' . $image->getClientOriginalName();
//                    $image->move(public_path('products-images'), $imageName);
//
//                    $productImage = new ProductImage([
//                        'image_path' => $imageName,
//                    ]);
//                    $productImage->product()->associate($product);
//                    $productImage->save();
//                }
//            }
//        }
    }
}
