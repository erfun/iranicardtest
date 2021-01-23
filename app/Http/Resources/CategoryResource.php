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
            "creator_name" => $this->user->name,
            "creator_family" => $this->user->family,
            "title" => $this->title,
        ];

        return $data;
    }
}
