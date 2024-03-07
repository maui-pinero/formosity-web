<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $data = [];
        $data['categories'] = Category::active()->count();

        $data['products'] = Product::count();

        $data['coupons'] = Coupon::whereDate('from_valid', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereDate('till_valid', '>=', Carbon::now())
                    ->orWhereNull('till_valid');
            })->count();

        $data['orders'] = Order::where('status', '!=', 'pending')->where('payment_status', 'Success')->count();

        return view('dpanel.dashboard', compact('data'));
    }
}
