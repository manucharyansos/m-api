<?php

namespace App\Http\Controllers\Api\Product;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::with('images')->withCount('reviews')->paginate(10);
        return response()->json(['productData' => $products]);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|integer',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'category_id' => $validatedData['category_id'],
        ]);

        foreach ($validatedData['images'] as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('products-images'), $imageName);

            $productImage = new ProductImage([
                'image_path' => $imageName,
            ]);

            $product->images()->save($productImage);
        }

        return response()->json(['message' => 'Product created successfully']);
    }


    public function edit($id): JsonResponse
    {
        $product = Product::with('images', 'reviews')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        return response()->json($product);
    }


    public function show($id): JsonResponse
    {
        $product = Product::with('images', 'reviews')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        if (empty($product->images)) {
            $product->images = []; // Set an empty array if images are not available
        }
        if (empty($product->reviews)) {
            $product->reviews = []; // Set an empty array if reviews are not available
        }
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $product->category_id = $validatedData['category_id'];
        $product->title = $validatedData['title'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }
        return response()->json($product, 200);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json('Product deleted successfully', 204);
    }
}
