<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::all();

        return Inertia::render('Product/Index', [
            'products' => $products,
        ]);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contents' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index');
    }

    public function delete(Request $request)
    {
        Product::whereIn('id', $request->items)->delete();
        
        return redirect()->route('product.index');
    }
}
