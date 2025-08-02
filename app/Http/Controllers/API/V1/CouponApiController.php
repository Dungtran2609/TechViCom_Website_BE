<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    // Lấy danh sách coupon
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('keyword')) {
            $query->where('code', 'like', '%' . $request->keyword . '%');
        }

        $coupons = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    // Lấy chi tiết coupon
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $coupon
        ]);
    }
}