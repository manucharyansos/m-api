<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;


class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }



    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->file('image')){
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->image->move(public_path('images'), $imageName);
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imageName
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
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation.', $validator->errors());
        }

        $input = $validator->validated();

        $product->title = $input['title'];
        $product->image = $input['image'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->save();

        return $this->successResponse('Product successfully updated.', new ProductResource($product));
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product does not exist.');
        }

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
