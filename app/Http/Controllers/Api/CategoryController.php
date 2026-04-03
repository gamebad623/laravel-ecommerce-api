<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return CategoryResource::collection(Category::all());
    }

    public function store(Request $request){
        $category = Category::create([
            'name' => $request->name,
        ]);

        return new CategoryResource($category);
    }

    public function show($id){
        $category = Category::findOrFail($id);

        return new CategoryResource($category);
    }

    public function update(Request $request , $id){
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
        ]);

        return new CategoryResource($category);
    }

    public function destroy($id){

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deteled successfully',
        ]);
    }


}
