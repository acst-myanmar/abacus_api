<?php

namespace App\Filters;

use App\Models\Category;

class BookFilter extends Filter
{
    public function name($value = "")
    {
        $this->builder->where('name', 'LIKE', "%$value%");
    }
    public function category($value = "")
    {
        $catIds = Category::where('slug', 'LIKE', "%$value%")->pluck('id')->toArray();
        $this->builder->whereIn('category_id', $catIds);
    }
    public function author($value = "")
    {
        $this->builder->where('author', 'LIKE', "%$value%");
    }
}
