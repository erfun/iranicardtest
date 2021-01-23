<?php


namespace App\Filters\PostFilters;


use App\Filters\QueryFilter;

class Key extends QueryFilter
{

    public function handle($value): void
    {
        $this->query->where("content", "LIKE", "%{$value}%");
    }
}
