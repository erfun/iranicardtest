<?php


namespace App\Filters\PostFilters;


use App\Filters\QueryFilter;

class User extends QueryFilter
{

    public function handle($value): void
    {
        $this->query->orwhere("user_id", $value);
    }
}
