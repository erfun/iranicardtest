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
            "creator_name" => isset($this->user->name)?$this->user->name:"unknown",
            "creator_family" => isset($this->user->family)?$this->user->family:"unknown",
            "category_id" => $this->category_id,
            "category_title" => isset($this->category->title)?$this->category->title:"none",

            "title" => $this->title,
            "content" => $this->content,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,

        ];

        return $data;
    }
}
