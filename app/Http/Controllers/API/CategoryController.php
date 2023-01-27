<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index(){
        $category = Category::all();
//        return $this->sendResponse(CategoryResource::collection($category), 'Products retrieved successfully.');
    return response()->json($category);
    }
}
