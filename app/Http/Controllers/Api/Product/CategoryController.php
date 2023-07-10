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
        $categories = Category::with('subcategories')->paginate(10);
        return response()->json(['category' => $categories]);
    }

    public function edit($id): JsonResponse
    {
        $category = Category::with('subcategories')->find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }
        return response()->json($category);
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

    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('category-images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    public function deleteImage($id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        if ($category->image) {
            $imagePath = public_path('category-images/' . $category->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
                $category->image = null;
                $category->save();
            }
        }

        return response()->json(['message' => 'Category image deleted successfully']);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json('Category deleted successfully', 204);
    }
}
