<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function getProducts(): JsonResponse
    {
        $products = Product::with('images')->withCount('reviews')->paginate(10);
        return response()->json(['productData' => $products]);
    }

    public function getCategories(): JsonResponse
    {
        $categories = Category::with('subcategories', 'products')->paginate(10);
        return response()->json(['category' => $categories]);
    }

    public function showProduct($id): JsonResponse
    {
        $product = Product::with('images', 'reviews')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        if (empty($product->images)) {
            $product->images = []; // Set an empty array if images are not available
        }
        if (empty($product->reviews)) {
            $product->reviews = []; // Set an empty array if reviews are not available
        }
        return response()->json($product);
    }

    public function findCategoryWithProducts($id): JsonResponse
    {
        $category = Category::with(['products', 'products.images'])->find($id);

        if ($category) {
            $category->products->each(function ($product) {
                $product->images = $product->images()->paginate(10);
            });
            return response()->json(['category' => $category]);
        } else {
            return response()->json(['error' => 'Category not found.'], 404);
        }
    }
}
