<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $categories = Category::with('subcategories')->get();
        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subcategory = new Category();
        $subcategory->name = $request->name;
        $subcategory->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('category-images'), $imageName);
            $subcategory->image = $imageName;
        }
        $subcategory->save();

        return response()->json([
            'message' => 'Subcategory created successfully',
            'subcategory' => $subcategory,
        ], 201);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json('Category deleted successfully', 204);
    }
}
