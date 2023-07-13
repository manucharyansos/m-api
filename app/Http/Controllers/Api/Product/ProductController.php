<?php

namespace App\Http\Controllers\Api\Product;


use App\Http\Controllers\Controller;
use App\Mail\OrderIn;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
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
            'subcategory_id' => 'required|integer',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $product = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'subcategory_id' => $validatedData['subcategory_id'],
        ]);
        foreach ($validatedData['images'] as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('storage/products-images'), $imageName);

            $productImage = new ProductImage([
                'image_path' => $imageName,
            ]);
            $product->images()->save($productImage);
        }

        return response()->json(['message' => 'Product created successfully']);
    }


    public function edit($id): JsonResponse
    {
        $product = Product::with('images', 'reviews.user')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        return response()->json($product);
    }


    public function show($id): JsonResponse
    {
        $product = Product::with('images', 'reviews.user')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        if (empty($product->images)) {
            $product->images = [];
        }
        if (empty($product->reviews)) {
            $product->reviews = [];
        }
        return response()->json($product);
    }


    public function updateProduct(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->subcategory_id = $request['subcategory_id'];
        $product->title = $request['title'];
        $product->description = $request['description'];
        $product->price = $request['price'];
        $product->stock = $request['stock'];
        $product->save();

        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('storage/products-images'), $imageName);

                $productImage = new ProductImage([
                    'image_path' => $imageName,
                ]);
                $product->images()->save($productImage);
            }
        }
//        Mail::to($request->user())->send(new OrderIn($product));
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

    public function deleteImage($imageId): JsonResponse
    {
        $image = ProductImage::find($imageId);

        if ($image) {
            Storage::disk('public')->delete($image->filename);
            $image->delete();
            return response()->json(['message' => 'Image deleted successfully']);
        }
        return response()->json(['message' => 'Image not found'], 404);
    }
}
