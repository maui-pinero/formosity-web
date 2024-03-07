<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;

class WishlistController extends Controller
{
    public function index()
    {

        $data = [];
        if(auth()->check()) {

            $user = User::find(auth()->user()->id);

            $data = $user->getFavoriteItems(Product::class)
                ->with([
                    'oldestImage',
                ])
            
                ->get();
        }
        
        return view('wishlist', compact('data'));
    }

    public function toggle($id)
    {
        $user = User::find(auth()->user()->id);
        $product = Product::find($id);

        $user->toggleFavorite($product);

        if ($user->hasFavorited($product)) {
            return response()->json(['msg' => 'Added successfully.', 'type' => 'ADDED']);
        } else {
            return response()->json(['msg' => 'Removed successfully.', 'type' => 'REMOVE']);
        }
    }
}
