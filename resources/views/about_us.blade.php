@extends('layouts.app')

@section('body_content')
    <section class="px-6 md:px-20 mt-6 min-h-screen">
    <h1 class="text-5xl font-bold text-center drop-shadow-md text-black py-12">About Us</h1>
        <div class="md:col-span-3 flex flex-col justify-center items-center gap-3">
            <img src="{{ asset('images/cute.png') }}" alt="">

                <p>Formosity is an online flower shop from flower enthusiasts, for flower enthusiasts. 
                    Our company aims to provide a user-friendly and convenient website/app for flower buyers to buy with ease. 
                    We offer exclusive discounts, limited-time collections, premium products, and more!</p><br>
                <p>Our skilled team guarantees the creation of each bouquet with freshly arranged flowers and carefully wrapped using high-quality materials. 
                    We provide a diverse range of bouquets in different colors, sizes, and arrangements, catering to a variety of celebrations and themes. 
                    This allows you to convey your unique message in a special way. </p>

        </div>
    </section>
@endsection