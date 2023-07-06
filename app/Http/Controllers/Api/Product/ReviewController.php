<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use PHPUnit\Exception;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        try {
            $review = new Review($validatedData);
            $review->product()->associate($product);
            $review->user()->associate($request->user());
            $review->save();

            $averageRating = $product->reviews()->average('rating');
            $product->average_rating = $averageRating;
            $product->save();

            return response()->json(['message' => 'Review created successfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function index(Product $product): JsonResponse
    {
        $reviews = $product->reviews()->with('user')->get();
        return response()->json($reviews);
    }
}
