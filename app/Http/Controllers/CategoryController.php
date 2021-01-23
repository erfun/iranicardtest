<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function userCategoryList()
    {
        return CategoryResource::collection(Category::where("user_id", Auth::id())->get());
    }

    public function create(CategoryRequest $request)
    {
        $request->validated();

        $new_category = Category::create([
            "user_id" => Auth::id(),
            "title" => $request->title,
        ]);

        if ($new_category)
            return response()->json(["status" => true, "cat_id" => $new_category->id]);
        else
            return response()->json(["status" => false, "message" => "something wrong,please contact with administrator."]);

    }

    public function update(CategoryRequest $request)
    {
        $request->validated();

        $category_details = Category::where("user_id", Auth::id())->where("id", $request->id)->first();

        if ($category_details) {

            $category_details->title = $request->title;
            $category_details->save();

            return response()->json(["status" => true, "message" => "category updated."]);
        } else
            return response()->json(["status" => false, "message" => "category not found OR you don`t have access for edit."]);
    }

}
