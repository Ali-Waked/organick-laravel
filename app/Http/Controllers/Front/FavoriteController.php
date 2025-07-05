<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Model\Favorite;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        return FavoriteResource::collection(
            Favorite::with(['product', 'product.category'])
                ->latest()
                ->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);
        Auth::user()->favorites()->firstOrCreate([
            'product_id' => $request->product_id,
        ]);
    }

    public function destroy(Product $product)
    {
        Auth::user()->favorites()->where('product_id', $product->id)->delete();
        return response()->noContent();
    }
}
