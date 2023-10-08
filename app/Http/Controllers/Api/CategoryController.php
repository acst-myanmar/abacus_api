<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::responseWithSuccess('category list', CategoryResource::collection(Category::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {

            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)

            ]);
            return ApiResponse::responseWithSuccess('successfully added');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $category)
    {
        try {
            $category = Category::find($category);

            if (!$category) {
                return ApiResponse::responseWithNotFound();
            }
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->save();

            return ApiResponse::responseWithSuccess('category updated successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        try {
            $category = Category::find($category);

            if (!$category) {
                return ApiResponse::responseWithNotFound();
            }
            $category->delete();
            return ApiResponse::responseWithSuccess('Category deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
