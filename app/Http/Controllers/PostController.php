<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    public function searchPost(Request $request)
    {
        return PostResource::collection(Post::where(function ($query) use ($request) {
            if (isset($request->creator_id)) {
                $query->orwhere("user_id", $request->creator_id);
            }
            if (isset($request->cat_id))
                $query->where("category_id", $request->cat_id);

            if (isset($request->keyword))
                $query->where("content", "LIKE", "%{$request->keyword}%");

        })->get());
    }

    public function list()
    {
        return PostResource::collection(Post::where("user_id", Auth::id())->get());
    }

    public function create(PostRequest $request)
    {
        $request->validated();

        $new_post = Post::create([
            "user_id" => Auth::id(),
            "category_id" => $request->cat_id,
            "title" => $request->title,
            "content" => $request->post_content
        ]);

        if ($new_post)
            return response()->json(["status" => true, "post_id" => $new_post->id]);
        else
            return response()->json(["status" => false, "message" => "something wrong,please contact with administrator."]);
    }

    public function update(PostRequest $request)
    {
        $request->validated();

        $post_details = Post::where("user_id", Auth::id())->where("id", $request->id)->first();

        if ($post_details) {

            $post_details->category_id = $request->cat_id;
            $post_details->title = $request->title;
            $post_details->content = $request->post_content;
            $post_details->save();

            return response()->json(["status" => true, "message" => "post has been updated."]);
        } else
            return response()->json(["status" => false, "message" => "post not found OR you don`t have access for edit."]);
    }
}
