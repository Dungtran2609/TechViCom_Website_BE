<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\GHNService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Tính phí vận chuyển từ GHN và lưu vào đơn hàng nếu có $orderId
     */
    public function calculateShipping(Request $request, $orderId, GHNService $ghnService): JsonResponse
    {
        $request->validate([
            'to_district_id'  => 'required|integer',
            'to_ward_code'    => 'required|string',
            'weight'          => 'required|numeric|min:1',
            'height'          => 'nullable|numeric|min:1',
            'length'          => 'nullable|numeric|min:1',
            'width'           => 'nullable|numeric|min:1',
            'insurance_value' => 'nullable|numeric|min:0',
            'service_id'      => 'nullable|integer',
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
            // Lấy serviceId nếu chưa truyền
            if ($request->filled('service_id')) {
                $serviceId = $request->input('service_id');
            } else {
                $availableServices = $ghnService->getAvailableServices($fromDistrict, $toDistrict);
                $serviceId         = $availableServices[0]['service_id'] ?? throw new \Exception("Không tìm thấy dịch vụ GHN phù hợp");
            }

            // Gọi API GHN để tính phí
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

            // Nếu truyền orderId thì gán shipping_fee vào đơn hàng
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->shipping_fee = $result['total'] ?? 0;
                    $order->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tính phí vận chuyển thành công',
                'data'    => [
                    'request'  => [
                        'service_id'       => $serviceId,
                        'from_district_id' => $fromDistrict,
                        'to_district_id'   => $toDistrict,
                        'to_ward_code'     => $toWardCode,
                        'weight'           => $weight,
                        'height'           => $height,
                        'length'           => $length,
                        'width'            => $width,
                        'insurance_value'  => $insuranceValue,
                    ],
                    'response' => $result,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('GHN Fee Error: ' . $e->getMessage());

            return response()->json([
                'error'   => true,
                'message' => 'Lỗi khi tính phí GHN: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API cho React Frontend - Tính phí vận chuyển không cần orderId
     */
    public function calculateShippingForFrontend(Request $request, GHNService $ghnService): JsonResponse
    {
        $request->validate([
            'to_district_id'  => 'required|integer',
            'to_ward_code'    => 'required|string',
            'weight'          => 'required|numeric|min:1',
            'height'          => 'nullable|numeric|min:1',
            'length'          => 'nullable|numeric|min:1',
            'width'           => 'nullable|numeric|min:1',
            'insurance_value' => 'nullable|numeric|min:0',
            'service_id'      => 'nullable|integer',
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
            // Lấy serviceId nếu chưa truyền
            if ($request->filled('service_id')) {
                $serviceId = $request->input('service_id');
            } else {
                $availableServices = $ghnService->getAvailableServices($fromDistrict, $toDistrict);
                $serviceId         = $availableServices[0]['service_id'] ?? throw new \Exception("Không tìm thấy dịch vụ GHN phù hợp");
            }

            // Gọi API GHN để tính phí
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
                'success' => true,
                'message' => 'Tính phí vận chuyển thành công',
                'data'    => [
                    'shipping_fee' => $result['total'] ?? 0,
                    'service_id'   => $serviceId,
                    'details'      => $result,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('GHN Fee Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính phí GHN: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API cho React Frontend - Lấy danh sách dịch vụ GHN
     */
    public function getAvailableServices(Request $request, GHNService $ghnService): JsonResponse
    {
        $request->validate([
            'to_district_id' => 'required|integer',
        ]);

        $fromDistrict = 1482; // Bắc Từ Liêm - Hà Nội
        $toDistrict   = $request->input('to_district_id');

        try {
            $services = $ghnService->getAvailableServices($fromDistrict, $toDistrict);

            return response()->json([
                'success' => true,
                'data'    => $services,
            ]);
        } catch (\Throwable $e) {
            \Log::error('GHN Services Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách dịch vụ: ' . $e->getMessage(),
            ], 500);
        }
    }
}
