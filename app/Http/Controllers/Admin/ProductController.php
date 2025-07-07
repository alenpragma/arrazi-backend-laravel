<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.pages.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'points' => 'required|integer',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'description' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('products', 'public');
        }

        Product::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'images' => $imagePath,
            'points' => $request->points,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'discount' => $request->discount,
            'description' => $request->description,
            'stock' => $request->input('stock'),
            'status' => $request->input('status'),

        ]);

        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('products', 'public');
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.pages.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'points' => 'required|integer',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'description' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $product->images;
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('products', 'public');
        }

        $product->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'images' => $imagePath,
            'points' => $request->points,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'discount' => $request->discount,
            'description' => $request->description,
            'stock' => $request->input('stock'),
            'status' => $request->input('status'),

        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
    public function show($id)
{
    $product = Product::findOrFail($id);
    return view('admin.pages.products.show', compact('product'));
}
}
