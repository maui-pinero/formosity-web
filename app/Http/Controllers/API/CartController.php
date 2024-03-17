<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem; // Import the CartItem model

class CartController extends Controller
{
    public function index()
    {
        // Retrieve all cart items from the database
        $cartItems = CartItem::all();
        
        // Return the cart items as JSON response
        return response()->json($cartItems);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'productId' => 'required|exists:products,id',
            'qty' => 'integer|min:1'
        ]);
        
        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->product_id = $request->input('productId');
        $cartItem->quantity = $request->input('qty', 1);
        $cartItem->save();
        
        // Return a success response
        return response()->json(['message' => 'Item added to cart']);
    }

    public function update(Request $request, $cartItemId)
    {
        // Validate the incoming request data
        $request->validate([
            'qty' => 'integer|min:1'
        ]);
        
        // Find the cart item by ID
        $cartItem = CartItem::find($cartItemId);
        
        // If the cart item is found, update its quantity
        if ($cartItem) {
            $cartItem->quantity = $request->input('qty', 1);
            $cartItem->save();
            return response()->json(['message' => 'Cart item updated']);
        }

        // If the cart item is not found, return an error response
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    public function destroy($cartItemId)
    {
        // Find the cart item by ID
        $cartItem = CartItem::find($cartItemId);
        
        // If the cart item is found, delete it
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Cart item removed']);
        }

        // If the cart item is not found, return an error response
        return response()->json(['error' => 'Cart item not found'], 404);
    }
}