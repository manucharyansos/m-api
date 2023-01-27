<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function index(){
        $category = Category::all();
//        return $this->sendResponse(CategoryResource::collection($category), 'Products retrieved successfully.');
    return response()->json($category);
    }

    //    public function category(){
//        $product = Product::with('category')->get();
//        return response()->json($product);
//    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category = Category::create([
            'name' => $request->name,
        ]);
        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }
}
