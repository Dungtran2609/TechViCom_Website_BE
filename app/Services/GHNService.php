<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GHNService
{
    protected $token;
    protected $shopId;
    protected $fromDistrictId;
    public function __construct()
    {
        $this->token = config('services.ghn.token');
        $this->shopId = config('services.ghn.shop_id');
        $this->fromDistrictId = config('services.ghn.from_district_id');

    }
public function getAvailableServices(int $fromDistrictId, int $toDistrictId): array
{
    $response = Http::withHeaders([
        'Token'  => $this->token,
        'ShopId' => $this->shopId,
    ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
        'from_district_id' => $fromDistrictId,
        'to_district_id'   => $toDistrictId,
        'shop_id'          => (int) $this->shopId,
    ]);

    if ($response->failed()) {
        logger()->error('GHN Service Error', ['response' => $response->body()]);
        return [];
    }

    return $response->json('data');
}





    public function calculateShippingFee(
        int $serviceId,
        float $insuranceValue,
        int $fromDistrictId,
        int $toDistrictId,
        string $toWardCode,
        int $height,
        int $length,
        int $weight,
        int $width
    ): array {
        $response = Http::withHeaders([
            'Token' => $this->token,
            'ShopId' => $this->shopId,
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', [
                    'service_id' => $serviceId,
                    'insurance_value' => $insuranceValue,
                    'from_district_id' => $fromDistrictId,
                    'to_district_id' => $toDistrictId,
                    'to_ward_code' => $toWardCode,
                    'height' => $height,
                    'length' => $length,
                    'weight' => $weight,
                    'width' => $width,
                ]);

        if (!$response->successful()) {
            throw new \Exception('GHN API lỗi khi tính phí: ' . $response->body());
        }

        return $response->json('data') ?? [];
    }
}
