@extends('layouts.app')

@section('body_content')
    <section class="px-6 md:px-20 mt-6 min-h-screen">
    <h1 class="text-5xl font-bold text-center drop-shadow-md text-black py-12">Wishlist</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            @forelse ($data as $item)
                <div class="flex gap-4 mb-5">
                    <div class="bg-gray-100 rounded shadow p-2">
                        <img class="w-20" src="{{ asset('storage/'.$item->oldestImage->path) }}" alt="">
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <h3 class="text-lg font-medium text-gray-800">{{ $item->title }}</h3>
                        <p class="text-black text-lg font-bold">₱{{ $item->selling_price }}
                            <sub class="text-sm font-normal text-red-500 strike"><strike>₱{{ $item->mrp }}</strike>
                                @php
                                    $discount = (($item->mrp - $item->selling_price) / $item->mrp)*100;
                                @endphp
                                <span class="text-green-400">({{ round($discount) }}% Off)</span>
                            </sub>
                        </p>
                        <div class="flex items-center gap-6">

                            <!-- <button onclick="buyNow('{{ $item->id }}')" class="text-rose-600 font-bold uppercase">Buy Now</button> -->
                            <button onclick="toggleWishlist(this, '{{ $item->id }}', true)" 
                                class="text-gray-400 uppercase">Remove</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 flex flex-col justify-center items-center gap-3">
                    <img src="{{ asset('images/empty_cart.png') }}" alt="">
                    <h1 class="text-2xl font-bold text-gray-800">Your wishlist is empty!</h1>
                </div>
            @endforelse
        </div>

    </section>
@endsection