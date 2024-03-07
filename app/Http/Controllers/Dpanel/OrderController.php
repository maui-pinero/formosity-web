<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $data = Order::query()
            ->with('coupon:id,code')
            ->latest()
            ->paginate(10);

        return view('dpanel.orders', compact('data'));
    }

    public function show($id)
    {
        $order = Order::with([
            'items.product:id,title',
            'items.product:oldestImage'
        ])
            ->where('id', $id)
            ->first();

        return view('dpanel.order', compact('order'));
    }
    
    public function updateStatus($id, $status)
    {
        Order::find($id)->update(['status' => $status]);

        return back()->withSuccess('Status changed successfully.');
    }
}
