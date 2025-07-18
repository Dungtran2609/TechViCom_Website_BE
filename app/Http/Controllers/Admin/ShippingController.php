<?php

namespace App\Http\Controllers;

use App\Services\GHNService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    public function calculateShipping(Request $request, GHNService $ghnService): JsonResponse
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code'   => 'required|string',
            'weight'         => 'required|numeric|min:1',
            'height'         => 'nullable|numeric|min:1',
            'length'         => 'nullable|numeric|min:1',
            'width'          => 'nullable|numeric|min:1',
            'insurance_value'=> 'nullable|numeric|min:0',
            'service_id'     => 'nullable|integer',
        ]);

        $insuranceValue = $request->input('insurance_value', 0);
        $fromDistrict   = 1482; // Bắc Từ Liêm - Hà Nội
        $toDistrict     = $request->input('to_district_id');
        $toWardCode     = $request->input('to_ward_code');
        $height         = $request->input('height', 10);
        $length         = $request->input('length', 10);
        $width          = $request->input('width', 10);
        $weight         = $request->input('weight');

        try {
            // Nếu không truyền service_id thì tự lấy từ GHN
            if ($request->filled('service_id')) {
                $serviceId = $request->input('service_id');
            } else {
                $availableServices = $ghnService->getAvailableServices($fromDistrict, $toDistrict);
                $serviceId = $availableServices[0]['service_id'] ?? throw new \Exception("Không tìm thấy dịch vụ GHN phù hợp");
            }

            $result = $ghnService->calculateShippingFee(
                $serviceId,
                $insuranceValue,
                $fromDistrict,
                $toDistrict,
                $toWardCode,
                $height,
                $length,
                $weight,
                $width
            );

            return response()->json([
                'request' => [
        'service_id' => $serviceId,
        'from_district_id' => $fromDistrict,
        'to_district_id' => $toDistrict,
        'to_ward_code' => $toWardCode,
        'weight' => $weight,
        'height' => $height,
        'length' => $length,
        'width' => $width,
        'insurance_value' => $insuranceValue,
    ],
    'response' => $result,
                
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Lỗi khi tính phí GHN: ' . $e->getMessage(),
            ], 500);
        }
    }
}
