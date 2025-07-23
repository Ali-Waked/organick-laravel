<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('q', '');

        if (!$query) {
            return response()->json([]);
        }

        return response()->json([
            'products' => Product::with('category')->where('name', 'like', "%$query%")->orWhereHas('category', function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%$query%");
            })->limit(5)->get(),
            'news' => News::where('title', 'like', "%$query%")->orWhere('subtitle', 'like', "%$query%")->orWhere('type', 'like', "%$query%")->limit(5)->get(),
        ]);

    }
}
