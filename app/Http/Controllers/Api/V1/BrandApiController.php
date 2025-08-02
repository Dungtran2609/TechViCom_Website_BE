<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandApiController extends Controller
{
    public function index()
    {
        $brands = Brand::where('status', 1)->get();
        return response()->json($brands);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }
}