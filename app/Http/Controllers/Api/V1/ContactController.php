<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Gửi form liên hệ
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $contact = Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ phản hồi sớm nhất có thể.',
                'data' => $contact
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi thông tin liên hệ. Vui lòng thử lại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin liên hệ của công ty
     */
    public function companyInfo(): JsonResponse
    {
        $companyInfo = [
            'name' => 'TechViCom',
            'address' => 'Toà nhà Ladeco, 266 Đội Cấn, phường Liễu Giai, Quận Ba Đình, Hà Nội',
            'phone' => '19006750',
            'email' => 'support@techvicom.com',
            'website' => 'https://techvicom.com',
            'working_hours' => 'Thứ 2 - Chủ nhật: 8:00 - 22:00',
            'description' => 'TechViCom - Công nghệ cho mọi nhà, giá tốt mỗi ngày. Chuyên cung cấp các sản phẩm công nghệ chất lượng cao với giá cả hợp lý.',
            'social_media' => [
                'facebook' => 'https://facebook.com/techvicom',
                'youtube' => 'https://youtube.com/techvicom',
                'instagram' => 'https://instagram.com/techvicom',
                'tiktok' => 'https://tiktok.com/@techvicom'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $companyInfo
        ]);
    }
} 