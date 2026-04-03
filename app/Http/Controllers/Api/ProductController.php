<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $product = Product::with('category')->get();
        return ProductResource::collection($product);
    }

    public function store(Request $request){
        $product = Product::create($request->all());
        return new ProductResource($product);
    }

    public function show($id){
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);

    }

    public function update(Request $request , $id){
        $product = Product::findOrFail($id);

        $product->update($request->all());

        return new ProductResource($product);
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
