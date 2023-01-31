<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(){
        $category = Category::all();
        return $this->sendResponse(CategoryResource::collection($category), 'Category retrieved successfully.');
//    return response()->json($category);
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
        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        unlink("category-images/".$category->image);
        if (is_null($category)) {
            return $this->sendError('Category does not exist.');
        }

        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
