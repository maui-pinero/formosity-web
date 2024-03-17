<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $wishlistItems = $user->getFavoriteItems(Product::class)
            ->with(['oldestImage'])
            ->get();

        return response()->json(['message' => 'Wishlist items retrieved successfully', 'data' => $wishlistItems], 200);
    }

    public function toggle($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $user->toggleFavorite($product);

        if ($user->hasFavorited($product)) {
            return response()->json(['message' => 'Product added to wishlist', 'type' => 'ADDED'], 200);
        } else {
            return response()->json(['message' => 'Product removed from wishlist', 'type' => 'REMOVED'], 200);
        }
    }
}
