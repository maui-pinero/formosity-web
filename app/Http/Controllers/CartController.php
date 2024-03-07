<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $addresses = [];

        if(auth()->check()) {
            $addresses = UserAddress::where('user_id', auth()->user()->id)->get();
        }

        return view('cart', compact('addresses'));
    }

    public function apiCartProducts (Request $request)
    {
        $ids = explode(',', $request->ids);

        $data = Product::with('image','oldestImage')->whereIn('id', $ids)->get();

        return response()->json($data);
    }

    public function apiApplyCoupon (Request $request)
    {
        $data = Coupon::where('code', $request->code)
            ->whereDate('from_valid', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereDate('till_valid', '>=', Carbon::now())
                    ->orWhereNull('till_valid');
            })->first();

            abort_if(!$data, 404, 'Invalid or expired coupon code');

            return response()->json($data);
    }
}
