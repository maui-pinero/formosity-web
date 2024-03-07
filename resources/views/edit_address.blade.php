@extends('layouts.app')

@push('scripts')
    <script>

    </script>
@endpush

@section('body_content')
    <div class="px-6 md:min-h-screen md:px-20 mt-6 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <ul class="flex md:flex-col flex-wrap justify-between gap-3 md:gap-1" id="tabLinks">
                <li><a class="flex" href="{{ route('account.index') }}">My Profile</a></li>
                <li><a class="flex" href="{{ route('account.index', ['tab' => 'orders']) }}">My Orders</a></li>
                <li><a class="flex text-rose-600 underline" href="{{ route('account.index', ['tab' => 'address']) }}">My Addresses</a></li>
                <li><a href="{{ route('logout') }}" class="flex">Logout</a></li>
            </ul>
        </div>

        <!-- right side -->
        <div class="md:col-span-5">
            @auth
                <section id="profile" class="tabContent border border-slate-300 rounded px-4 pt-2 pb-4">
                    <h3 class="text-gray-900 font-medium text-center">Edit Delivery Address</h3>
                    <hr class="mb-3">

                    <form action="{{ route('address.update', $data->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @method('PUT')
                        @csrf
                        <!-- is_default_address -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Default Address</label>
                            <select name="is_default_address" class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                                <option value="">Select</option>
                                <option value="1" @selected($data->is_default_address = 1)>Yes</option>
                                <option value="0" @selected($data->is_default_address = 0)>No</option>
                            </select>
                        </div>

                        <!-- tag -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Address Type</label>
                            <select name="tag" class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                                <option value="">Select</option>
                                <option value="Home" @selected($data->tag == 'Home')>Home</option>
                                <option value="Office" @selected($data->tag == 'Office')>Office</option>
                            </select>
                        </div>

                        <!-- first name -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >First Name</label>
                            <input type="text" name="first_name" value="{{ $data->first_name }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- last name -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Last Name</label>
                            <input type="text" name="last_name" value="{{ $data->last_name }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- mobile number -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Mobile Number</label>
                            <input type="tel" maxlength="11" name="mobile_no" value="{{ $data->mobile_no }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- street address -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Street Address</label>
                            <input type="text" name="street_address" value="{{ $data->street_address }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- barangay -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Barangay</label>
                            <input type="text" name="barangay" value="{{ $data->barangay }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- city -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >City</label>
                            <input type="text" name="city" value="{{ $data->city }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- province -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Province</label>
                            <input type="text" name="province" value="{{ $data->province }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- zip code -->
                        <div class="mt-4 relative border border-slate-300 rounded">
                            <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400" >Zip Code</label>
                            <input type="tel" maxlength="4" name="zip_code" value="{{ $data->zip_code }}"
                                class="mt-2 px-3 bg-transparent focus:outline-none w-full" required>
                        </div>

                        <!-- note -->

                        <div></div>
                        <div>
                            <label for="">&nbsp</label>
                            <button
                                class="bg-rose-500 rounded shadow py-1 text-center w-full text-white uppercase font-medium">Update</button>
                        </div>

                    </form>
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