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
        $categories = Category::with('subcategories')->paginate(10);
        return response()->json(['category' => $categories]);
    }

    public function showProduct($id): JsonResponse
    {
        $product = Product::with('images', 'reviews')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        if (empty($product->images)) {
            $product->images = [];
        }
        if (empty($product->reviews)) {
            $product->reviews = [];
        }
        return response()->json($product);
    }

    public function findCategoryWithProducts($id): JsonResponse
    {
        $category = Category::with(['subcategories.products.images'])->find($id);

        if ($category) {
            return response()->json(['category' => $category]);
        } else {
            return response()->json(['error' => 'Category not found.'], 404);
        }
    }
}
