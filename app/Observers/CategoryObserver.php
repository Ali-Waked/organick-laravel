<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function saving(Category $category)
    {
        $slug = Str::slug($category->name);
        $count = Category::whereLike('slug',  "{$slug}%")->where('id', '<>', $category->id)->count();
        if ($count) {
            $slug .= '-' . $count + 1;
        }
        $category->slug = $slug;
    }
}
