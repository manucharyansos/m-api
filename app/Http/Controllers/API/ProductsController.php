<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ApiController;

class ProductsController extends ApiController
{
    public function index()
    {
        $products = Product::all();
//        return $this->successResponse('Products successfully fetched.', ProductResource::collection(Product::all()));
        return response()->json($products);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error validation.', $validator->errors());
        }
        if ($request->file('image')){
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->image->move(public_path('images'), $imageName);
            Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imageName
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Product created successfully',
            ]);
        }

        return $this->successResponse('Post successfully created.', new ProductResource(
            Product::create($validator->validated())
        ));
    }


    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->errorResponse('Product does not exist.');
        }
        return $this->successResponse('Product successfully fetched.', new ProductResource($product));
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
            return $this->errorResponse('Error validation.', $validator->errors());
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
            return $this->errorResponse('Product does not exist.');
        }

        $product->delete();

        return $this->successResponse('Product successfully deleted.');
    }
}
