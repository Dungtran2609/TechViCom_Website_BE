<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'brand', 
            'category', 
            'allImages',
            'variants.attributeValues.attribute'
        ])
        ->where('status', 'active')
        ->orderByDesc('id')
        ->paginate(10);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with([
            'brand', 
            'category', 
            'allImages',
            'variants.attributeValues.attribute'
        ])->findOrFail($id);

        return response()->json($product);
    }
}