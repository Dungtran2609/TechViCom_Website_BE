<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerApiController extends Controller
{
    // Lấy danh sách banner
    public function index(Request $request)
    {
        $query = Banner::query();

        // Tìm kiếm theo từ khóa (link)
        if ($request->filled('keyword')) {
            $query->where('link', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày bắt đầu/kết thúc nếu cần
        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }
        if ($request->filled('end_date_from')) {
            $query->whereDate('end_date', '>=', $request->end_date_from);
        }
        if ($request->filled('end_date_to')) {
            $query->whereDate('end_date', '<=', $request->end_date_to);
        }

        $banners = $query->orderBy('stt', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    // Lấy chi tiết banner
    public function show($id)
    {
        $banner = Banner::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }
}