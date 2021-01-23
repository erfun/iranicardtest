<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "creator_name" => isset($this->user->name) ? $this->user->name : "unknown",
            "creator_family" => isset($this->user->family) ? $this->user->family : "unknown",
            "title" => $this->title,
        ];

        return $data;
    }
}
