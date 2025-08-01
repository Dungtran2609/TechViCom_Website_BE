<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantApiController extends Controller
{
    // Danh sách biến thể theo product_id
    public function index(Request $request)
    {
        $productId = $request->query('product_id');
        $query = ProductVariant::with(['attributeValues.attribute']);
        if ($productId) {
            $query->where('product_id', $productId);
        }
        $variants = $query->orderByDesc('id')->paginate(10);
        return response()->json($variants);
    }

    // Chi tiết biến thể
    public function show($id)
    {
        $variant = ProductVariant::with(['attributeValues.attribute', 'product'])->findOrFail($id);
        return response()->json($variant);
    }
}
