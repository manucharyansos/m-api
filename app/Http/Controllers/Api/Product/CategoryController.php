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
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): JsonResponse
    {
        $category = Category::all();
        return response()->json($category);
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->file('image')){
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->image->move(public_path('category-images'), $imageName);
            $category = Category::create([
                'image' => $imageName,
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }
        return response()->json($category, 'Category created successfully.');
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);
        unlink("category-images/".$category->image);
        if (is_null($category)) {
            return response()->json('Category does not exist.');
        }

        $category->delete();

        return response()->json([], 'Category deleted successfully.');
    }
}
