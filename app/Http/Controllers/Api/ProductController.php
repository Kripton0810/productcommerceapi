<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Product::all(), 'message' => 'Product has been fetched successfully']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'rate' => 'nullable|integer',
            'stock' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
        }
        $product = Product::create($request->all());
        return response()->json(['success' => true, 'data' => $product, 'message' => 'Product has been created successfully']);
    }

    public function show(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        try {
            return response()->json(['success' => true, 'data' => $product, 'message' => 'Product has been fetched successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error ', 'errors' => $th->getMessage()], 422); // Return validation errors as JSON
        }
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'rate' => 'nullable|integer',
            'stock' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
        }

        $product->update($request->all());

        return response()->json(['success' => true, 'data' => $product, 'message' => 'Product has been updated successfully']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product has been deleted successfully']);
    }
}
