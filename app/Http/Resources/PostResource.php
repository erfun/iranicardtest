<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [

            "id" => $this->id,
            "creator_id" => $this->user_id,
            "creator_name" => $this->user->name,
            "creator_family" => $this->user->family,
            "category_id" => $this->category_id,
            "category_title" => $this->category->title,

            "title" => $this->title,
            "content" => $this->content,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,

        ];

        return $data;
    }
}
