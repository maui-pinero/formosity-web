<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::paginate(20);

        return view('dpanel.category.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories'
        ]);

        $data = new Category();
        $data -> name = $request -> name;
        $data -> slug = Str::slug($request->name);
        $data -> save();

        return back()->withSuccess('New category added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id
        ]);

        $data = Category::find($id);
        $data -> name = $request -> name;
        $data -> slug = Str::slug($request->name);
        $data -> is_active = $request -> is_active;
        $data -> save();

        return back()->withSuccess('Category updated successfully.');
    }
}
