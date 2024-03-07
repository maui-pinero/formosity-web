<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::with('category')->paginate(10);

        return view('dpanel.product.index', compact('data'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('dpanel.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'=>'required',
            'title'=>'required|max:255|unique:products',
            'description'=>'required',
            'sku'=>'required',
            'mrp'=>'required',
            'selling_price'=>'required',
            'stock'=>'required',
            'images.*' => 'mimes:jpg,jpeg,png',
        ]);

        // store product
        $product = new Product;
        $product->category_id = $request->category_id;
        $product->title = $request->title;
        $product->slug = Str::slug($request->title);
        $product->description = $request->description;
        $product->sku = $request->sku;
        $product->mrp = $request->mrp;
        $product->selling_price = $request->selling_price;
        $product->stock = $request->stock;
        $product->save();

        // store images

        foreach ($request->images as $image) {
            $productImage = new ProductImage;
            $productImage->product_id = $product->id;
            $productImage->path = $image->store('media', 'public');
            $productImage->save();
        }

        return redirect()->route('dpanel.product.index')->withSuccess('Product added successfully.');
    }

    public function edit($id)
    {
        $data = Product::with('image')->find($id);

        abort_if(!$data, 404);

        $categories = Category::where('is_active', true)->get();

        return view('dpanel.product.edit', compact('categories','data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'=>'required',
            'title'=>'required|max:255|unique:products,title,'. $id,
            'description'=>'required',
            'sku'=>'required',
            'mrp'=>'required',
            'selling_price'=>'required',
            'stock'=>'required',
            'images.*' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        // store product
        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->title = $request->title;
        $product->slug = Str::slug($request->title);
        $product->description = $request->description;
        $product->sku = $request->sku;
        $product->mrp = $request->mrp;
        $product->selling_price = $request->selling_price;
        $product->stock = $request->stock;
        $product->save();

        // store images

        if($request->images) {
            foreach ($request -> images as $key => $image) {

                if(isset($request->image_ids[$key])) {
                    $productImage = ProductImage::find($request->image_ids[$key]);
                    Storage::disk('public')->delete($productImage->path);
                    $productImage->path = $image->store('media', 'public');
                    $productImage->save();
                } else {
                    $productImage = new ProductImage;
                    $productImage->product_id = $product->id;
                    $productImage->path = $image->store('media', 'public');
                    $productImage->save();
                }
            }
        }

        return redirect()->route('dpanel.product.index')->withSuccess('Product updated successfully.');
    }
}
