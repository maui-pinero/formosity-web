@extends('layouts.app')

@push('scripts')
    <x-url-generator-js/>
    <script>
        let currentImage = 0;

        const viewImage = (e, index) => {

            currentImage = index;

            document.getElementById('bigImage').src = e.querySelector('img').src;
        }

        const nextPrevious = (index) => {

            i = currentImage + index;

            let images = document.getElementById('images').querySelectorAll('img');

            if (i >= images.length || i < 0) return;

            currentImage = i;

            let arr = [];

            images.forEach(element => arr.push(element.src));

            document.getElementById('bigImage').src = arr[currentImage];
        }

        const addToCart = () => {
            let productId = '{{ $product->id }}';
            mCart.add(productId, 1);
            cuteToast({
                type: 'success',
                message: 'Added to cart'
            });

            document.getElementById('add_to_cart_btn').innerHTML = 'Added To Cart';
            cartCount();
            return true;
        }

        const buyNow = () => {
            if (addToCart()) {
                window.location.href = "{{ route('cart') }}";
            }
        }

        @if ($product->count() == 1)
            let productId = '{{ $product->id }}';
            if (mCart.isInCart(productId))
                document.getElementById('add_to_cart_btn').innerHTML = 'Added To Cart';
        @endif

    </script>
@endpush

@section('body_content')
    <section class="px-6 md:px-20 mt-6">
        <div class="flex flex-wrap md:flex-nowrap gap-6">

            <!-- left -->

            <div class="shrink-0 md:w-auto flex flex-col-reverse md:flex-row gap-4">
                <div id="images" class="flex md:flex-col gap-3 pb-1 md:pb-0 max-h-96 overflow-y-auto">
                    @foreach ($product->image as $image)
                        <div onclick="viewImage(this, '{{$image->id}}')" class="bg-white rounded-md shadow p-1 cursor-pointer">
                            <img class="w-14" src="{{ asset('storage/'.$image->path ) }}"
                                alt="">
                        </div>
                    @endforeach
                </div>
                <div class="relative bg-white rounded-md shadow-md p-3">
                    <img id="bigImage" class="aspect-[2/2]" src="{{ asset('storage/'.$product->image[0]->path ) }}" alt="">

                    <span onclick="nextPrevious(-1)" class="absolute top-1/2 left-1 bg-white rounded-full w-5 h-5 shadow flex items-center justify-center">
                        <i class='bx bx-chevron-left text-xl text-gray-400 hover-text-rose-600 duration-200 cursor-pointer'></i>
                    </span>
                    <span onclick="nextPrevious(1)" class="absolute top-1/2 right-1 bg-white rounded-full w-5 h-5 shadow flex items-center justify-center">
                        <i class='bx bx-chevron-right text-xl text-gray-400 hover-text-rose-600 duration-200 cursor-pointer'></i>
                    </span>
                </div>
            </div>

            <!-- left end -->

            <!-- right -->
            <div class="w-full flex flex-col gap-4">
                <div class="flex gap-3">
                    @php
                        $discount = (($product->mrp - $product->selling_price) / $product->mrp)*100;
                    @endphp
                    <span class="bg-red-400 text-white rounded px-2 text-xs"> {{ round($discount) }}% off </span>
                    <span class="text-gray-400 text-sm"><i class='bx bx-star'></i> 4.5 </span>
                </div>

                <!-- name, sku -->
                <h2 class="text-lg font-medium text-gray-800">{{ $product->title }}</h2>
                <div class="text-sm text-gray-800">
                    <p><span class="text-gray-400">SKU: </span>{{ $product->sku }}</p>
                </div>

                <!-- price -->
                <div>
                    <span class="text-rose-500 font-bold text-xl">₱{{ $product->selling_price }}</span>
                    <sub class="text-gray-400"><strike>₱{{ $product->mrp }}</strike></sub>
                </div>

                <!-- quantity -->
                <!-- <div>
                    <p class="text-gray-400">Quantity</p>
                    <div class="flex items-center gap-2">
                        <input type="text" value="1" readonly
                        class="bg-slate-200 rounded border border-gray-300 focus:outline-none px-2 text-lg font-medium w-20">
                        <button class="rounded border border-gray-300 w-7 h-7"><i class='bx bx-minus text-xl'></i></button>
                        <button class="rounded border border-gray-300 w-7 h-7"><i class='bx bx-plus text-xl'></i></button>
                    </div>
                </div> -->

                <!-- wishlist, add to cart and buy now -->
                <div class="flex items-center gap-4">
                    <span class="bg-white shadow-md rounded-full w-8 h-8 flex items-center justify-center">
                    <button onclick="toggleWishlist(this, '{{ $product->id }}')" class="bg-white shadow-md rounded-full w-7 h-7 flex items-center justify-center">
                        <i class='bx {{ $product->has_favorited ? 'bxs-heart text-red-500' : 'bx-heart' }} text-xl'></i>
                    </button>
                    </span>

                    <button onclick="addToCart()" id="add_to_cart_btn"
                        class="border border-rose-600 rounded w-28 text-center drop-shadow font-medium text-rose-600 py-0.5">Add to Cart</button>
                    <button onclick="buyNow()"
                        class="border border-rose-600 rounded w-28 text-center drop-shadow font-medium text-white bg-rose-600 py-0.5">Buy Now</button>
                </div>

            </div>

            <!-- right end -->

        </div>

        <!-- product description -->
        <div>
            <h3 class="text-lg text-gray-400 font-medium my-6">Product Description</h3>
            <div class="text-gray-600">{{ $product->description }}
            </div>
        </div>

        <section class="mt-6">
            <h3 class="text-gray-800 font-medium mb-2">Featured Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach ($products->slice(0, 4) as $item)
                    <x-product.card1 :product="$item"/>
                @endforeach
            </div>
        </section>

    </section>
@endsection