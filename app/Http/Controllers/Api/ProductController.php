<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'rate' => 'nullable|integer',
            'stock' => 'nullable|integer',
        ]);

        return Product::create($request->all());
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'rate' => 'nullable|integer',
            'stock' => 'nullable|integer',
        ]);

        $product->update($request->all());

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
