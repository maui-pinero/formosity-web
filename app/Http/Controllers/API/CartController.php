<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')->get();
        
        return response()->json($cartItems);
    }

    public function store(Request $request)
    {
        $request->validate([
            'productId' => 'required|exists:products,id',
            'qty' => [
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::find($request->input('productId'));
                    if (!$product) {
                        $fail('The selected product is invalid.');
                        return;
                    }
                    if ($value > $product->stock) {
                        $fail('The requested quantity exceeds the available stock.');
                    }
                },
            ],
        ]);
        
        $cartItem = new CartItem();
        $cartItem->product_id = $request->input('productId');
        $cartItem->quantity = $request->input('qty', 1);
        $cartItem->save();
        
        return response()->json(['message' => 'Item added to cart']);
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'qty' => 'integer|min:1'
        ]);
        
        $cartItem = CartItem::find($cartItemId);
        
        if ($cartItem) {
            $cartItem->quantity = $request->input('qty', 1);
            $cartItem->save();
            return response()->json(['message' => 'Cart item updated']);
        }

        return response()->json(['error' => 'Cart item not found'], 404);
    }

    public function destroy($cartItemId)
    {
        $cartItem = CartItem::find($cartItemId);
        
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Cart item removed']);
        }

        return response()->json(['error' => 'Cart item not found'], 404);
    }
}
