@extends('layouts.app')

@section('body_content')
    <div class="px-6 md:min-h-screen md:px-20 mt-6 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <ul class="flex md:flex-col flex-wrap justify-between gap-3 md:gap-1" id="tabLinks">
                <li><a class="flex" href="{{ route('account.index') }}">My Profile</a></li>
                <li><a class="flex text-rose-600 underline" href="{{ route('account.index', ['tab' => 'orders']) }}">My Orders</a></li>
                <li><a class="flex" href="{{ route('account.index', ['tab' => 'address']) }}">My Addresses</a></li>
                @auth
                    <li><a href="{{ route('logout') }}" class="flex">Logout</a></li>
                @endauth
            </ul>
        </div>

        <!-- right side -->
        <div class="md:col-span-5">
            @auth
                <section class="tabContent border border-slate-300 rounded px-4 pt-2 pb-4">
                    <h3 class="text-gray-900 font-medium">Order Details {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h3>
                    <hr class="mb-3">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Order ID</span>
                            <p class="flex-[3]">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Order Status</span>
                            <p class="flex-[3]">{{ $order->status }}</p>
                        </div>

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Payment Status</span>
                            <p class="flex-[3]">{{ $order->payment_status }}</p>
                        </div>

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Total ₱</span>
                            <p class="flex-[3]">{{ $order->total_amount }}</p>
                        </div>

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Discount ₱</span>
                            <p class="flex-[3]">{{ $order->discount_amount }}</p>
                        </div>

                        <div class="flex gap-1 border border-slate-300 rounded">
                            <span class="flex-[2] bg-slate-400 px-2 text-white font-medium rounded-l">Payment ₱</span>
                            <p class="flex-[3]">{{ $order->total_amount - $order->discount_amount }}</p>
                        </div>

                    </div>

                    <h3 class="text-gray-900 font-medium mt-8">Ordered Items</h3>
                    <hr class="mb-3">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($order->items as $items)
                        <div class="flex gap-4 mb-5">
                            <div class="bg-gray-100 rounded shadow p-2">
                                <img class="w-20" src="{{ asset('storage/'. $item->product->oldestImage->path) }}" alt="">
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <h3 class="text-lg font-medium text-gray-800">{{ $item->product->title }}</h3>
                                <p class="text-gray-400">
                                    Quantity: <span class="text-gray-800">{{ $item->qty }}</span>
                                </p>
                                <p class="text-gray-400">
                                    Price: ₱<span class="text-gray-800">{{ $item->price }}</span>
                                </p>
                                <p class="text-gray-400">
                                    Total: <span class="text-gray-800">{{ $item->price * $item->qty }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                </section>
            @else
                <div class="border w-full py-10 flex justify-center rounded-md items-center">
                    <button type="button" class="text-rose-500 font-medium" 
                        onclick="toggleLoginPopup()">Login to continue</button>
                </div>
            @endauth
        </div>
        <!-- right side end -->
    </div>
@endsection