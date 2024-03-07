<div class="bg-white rounded-lg shadow-lg p-3 relative">
    <a href="{{ route('product_detail', $product->slug) }}">
        <img class="mx-auto" src="{{ asset('storage/'.$product->image[0]->path) }}" alt="">
    </a>

    <div class="flex justify-between gap-3 my-3">
        <a href="{{ route('product_detail', $product->slug) }}"
            class=" font-medium text-gray-800">{{ $product->title }}</a>
        <div class="flex flex-col items-end">
            <strong class="text-rose-600">₱{{ $product->selling_price }}</strong>
            <strike class="text-gray-400">₱{{ $product->mrp }}</strike>
        </div>
    </div>

    <div class="flex justify-between items-center mb-3">
        <span class="text-gray-400"><i class='bx bx-star'></i> 4.5 </span>
        <a href="{{ route('product_detail', $product->slug) }}"
            class="text-rose-600 flex items-center font-bold"><i class='bx bx-cart-add text-2xl'></i> Buy Now </a>
    </div>

    <div class="absolute top-2 left-3 right-3 flex justify-between items-center">
        @php
            $discount = (($product->mrp - $product->selling_price) / $product->mrp)*100;
        @endphp
        <span class="bg-red-400 text-white rounded px-2">{{ round($discount) }}% Off</span>
        <button onclick="toggleWishlist(this, '{{ $product->id }}')" class="bg-white shadow-md rounded-full w-7 h-7 flex items-center justify-center">
            <i class='bx {{ $product->has_favorited ? 'bxs-heart text-red-500' : 'bx-heart' }} text-xl'></i>
            </button>
    </div>
</div>