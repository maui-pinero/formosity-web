<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('image')->latest()->limit(12)->get();

        if(auth()->check()) {
            $user = User::find(auth()->user()->id);
            $products = $user->attachFavoriteStatus($products);
        }
        
        $coupons = Coupon::whereDate('from_valid', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereDate('till_valid', '>=', Carbon::now())
                    ->orWhereNull('till_valid');
            })->get();

        $banners = Banner::active()->InRandomOrder()->limit(5)->get();
       
        return view('welcome', compact('products', 'coupons', 'banners'));
    }

    public function productDetail($slug)
    {
        $product = Product::with('image')->where('slug', $slug)->first();

        abort_if(!$product, 404);

        $products = Product::with('image')->latest()->limit(12)->get();

        // return $product;

        return view('product_detail', compact('products', 'product'));
    }

    public function products(Request $request)
    {

        $search = $request->k ?? null;

        $query = Product::query();

        // SEARCH FROM PRODUCT TITLE AND DESCRIPTION

        $query->when
            ($search, fn($q)
            => $q->where('title', 'LIKE', '%' . $search . '%')->where('description', 'LIKE', '%' . $search . '%')
        );

        // FILTER BY PRICE

        $query->where(function ($q) use ($request) {

            $price_min = $request->min ?? null;
            $q->when($price_min, fn ($q2)=> $q2->where('selling_price', '>=', $price_min));

            $price_max = $request->max ?? null;
            $q->when($price_max, fn ($q2)=> $q2->where('selling_price', '<=', $price_max));

        });

        $query->with('image')
            ->withCount('image')
            ->havingRaw('image_count > 0');

        // SORT BY FILTER
        if(in_array($request->sb, ['price_asc', 'price_desc'])){
            $query->orderBy('selling_price', substr($request->sb, 6));

        } elseif ($request->sb == 'desc') {
            $query->orderBy('updated_at', 'desc');
        }

        $products = $query->paginate(16);

        if(auth()->check()) {
            $user = User::find(auth()->user()->id);
            $products = $user->attachFavoriteStatus($products);
        }

        $banners = Banner::active()->InRandomOrder()->limit(5)->get();

        return view('products', compact('products', 'banners'));
    }
}
