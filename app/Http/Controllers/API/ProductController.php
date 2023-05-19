<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;


class ProductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index(): JsonResponse
    {
        $product = Product::with('category')->get();
        return $this->sendResponse(ProductResource::collection($product), 'Products retrieved successfully.');
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'price' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->file('image')){
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->image->move(public_path('images'), $imageName);
            $product = Product::create([
                'image' => $imageName,
                'category_id' => $request->categories,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
            ]);
        }
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }


    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }


    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'categories' => 'required|min:3',
            'title' => 'required|min:3',
            'description' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation.', $validator->errors());
        }

        $input = $validator->validated();

        $product->image = $input['image'];
        $product->categories = $input['categories'];
        $product->title = $input['title'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->save();

        return $this->successResponse('Product successfully updated.', new ProductResource($product));
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        unlink("images/".$product->image);
        if (is_null($product)) {
            return $this->sendError('Product does not exist.');
        }

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
