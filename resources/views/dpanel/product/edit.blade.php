@extends('dpanel.layouts.app')

@section('title','Edit Product')

@push('scripts')
    <script>

        const addMoreImage = () => {
            let id = 'img-'+Math.floor(Math.random()*10000);
            let html = `<div class="relative">
                            <label for="${id}"
                                class="flex items-center justify-center bg-white rounded-md shadow-md p-1 cursor-pointer">
                                <input type="file" id="${id}" name="images[]" onchange="setImagePreview(this, event)" accept="image/*" class="hidden">
                                <img src="https://placehold.jp/400x400.png?text=Add%20Image" 
                                    class="rounded-md aspect-[2/2] object-cover" alt="">
                            </label>
                        </div>`;
            document.getElementById('image_container').lastElementChild.insertAdjacentHTML('afterend', html);
        }

        const setImagePreview = (r, e, isAdd=true) => {
            if (e.target.files.length > 0) {
                r.setAttribute('onchange', 'setImagePreview(this, event, false)');
                r.nextElementSibling.src = URL.createObjectURL(e.target.files[0]);

                let span = 
                    `<span onclick="deleteImage(this)" class="absolute top-1 right-1 cursor-pointer w-7 h-7 flex items-center
                    justify-center bg-white hover:bg-rose-500 bg-opacity-25 hover:bg-opacity-100 text-rose-500 hover:text-white
                    duration-300 shadow rounded-full">
                    <i class='bx bx-trash text-xl'></i>
                        </span>`;
                r.insertAdjacentHTML('afterend', span);

                if (isAdd) addMoreImage();
            }
        }

        const deleteImage = e => e.parentElement.remove();

    </script>
@endpush

@section('body_content')
    <div class="bg-gray-800 flex justify-between items-center rounded-l pl-2 mb-3">
        <p class="text-white font-medium text-lg py-1">Edit Product</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-500 px-2 py-1 rounded border border-red-500 mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dpanel.product.update', $data->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- product basic details -->
        <section class="bg-white px-3 pb-3 rounded mb-3">
            <h2 class="mb-1 pt-2 text-lg font-medium text-gray-900">Product Basic Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 gap-x-4">
                <div>
                    <label>Product Category</label>
                    <select name="category_id" class="w-full bg-white border border-gray-700 rounded py-0.5 focus:outline-none">
                        <option value="">Select</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @selected($data->category_id==$item->id)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Product Name / Title</label>
                    <input type="text" name="title" value="{{ $data->title }}" placeholder="Enter Product Name/Title"
                        class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none">
                </div>

                <div class="md:col-span-3">
                    <label>Product Description</label>
                    <textarea name="description" rows="3" placeholder="Enter Product Description" 
                        class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none">{{ $data->description }}</textarea>
                </div>

                <div>
                    <label>SKU</label>
                    <input type="text" name="sku" value="{{ $data->sku }}" placeholder="Enter SKU"
                            class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none" required>
                </div>

                <div>
                    <label>MRP</label>
                    <input type="number" name="mrp" value="{{ $data->mrp }}" placeholder="Enter MRP"
                            class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none" required>
                </div>

                <div>
                    <label>Selling Price</label>
                    <input type="number" name="selling_price" value="{{ $data->selling_price }}" placeholder="Enter Selling Price"
                            class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none" required>
                </div>

                <div>
                    <label>Stocks</label>
                    <input type="number" name="stock" value="{{ $data->stock }}" placeholder="Enter Available Stocks"
                            class="w-full bg-transparent border border-gray-700 rounded py-0.5 px-2 focus:outline-none" required>
                </div>
            </div>
        </section>

        <!-- product image -->
        <section class="bg-white px-3 pb-3 rounded mb-3">
            <h2 class="mb-1 pt-2 text-lg font-medium text-gray-900">Product Images</h2>
            <div id="image_container" class="grid grid-cols-1 md:grid-cols-8 gap-3">

                @foreach ($data->image as $item)
                    <input type="hidden" name="image_ids[]" value="{{ $item->id }}">
                    <div class="relative">
                        <label for="img-{{ $item->id }}"
                            class="flex items-center justify-center bg-white rounded-md shadow-md p-1 cursor-pointer">
                            <input type="file" id="img-{{ $item->id }}" name="images[]" onchange="setImagePreview(this, event)" accept="image/*" class="hidden">
                            <img src="{{ asset('storage/'.$item->path) }}" 
                                class="rounded-md aspect-[2/2] object-cover" alt="">
                        </label>
                        <span onclick="deleteImage(this)"
                            class="absolute top-1 right-1 cursor-pointer w-7 h-7 flex items-center
                    justify-center bg-white hover:bg-rose-500 bg-opacity-25 hover:bg-opacity-100 text-rose-500 hover:text-white
                    duration-300 shadow rounded-full">
                            <i class='bx bx-trash text-xl'></i>
                        </span>
                    </div>
                @endforeach

                <div class="relative">
                    <label for="addMore"
                        class="flex items-center justify-center bg-white rounded-md shadow-md p-1 cursor-pointer">
                        <input type="file" id="img-1" name="images[]" onchange="setImagePreview(this, event)" accept="image/*" class="hidden">
                        <img src="https://placehold.jp/400x400.png?text=Add%20Image" 
                            class="rounded-md aspect-[2/2] object-cover" alt="">
                    </label>
                </div>

            </div>
        </section>

        <button class="bg-rose-500 text-center text-white font-medium w-full py-1 rounded shadow-md uppercase">Update Product</button>

    </form>
@endsection