<?php


namespace App\Filters\PostFilters;


use App\Filters\QueryFilter;

class Category extends QueryFilter
{

    public function handle($value): void
    {
        $this->query->where("category_id", $value);
    }
}
