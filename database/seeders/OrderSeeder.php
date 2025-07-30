<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users  = DB::table('users')->get();
        $orders = [];

        foreach ($users as $user) {
            $address = DB::table('user_addresses')
                ->where('user_id', $user->id)
                ->where('is_default', true)
                ->first();

            if (! $address) {
                continue;
            }

            $totalAmount = 500000; // giả định đơn hàng mẫu
            $totalWeight = 1.5;    // kg

            $shippingFee = 35000; // mặc định nếu GHN lỗi

            try {
                $district = DB::table('districts')->where('id', $address->district_id)->first();
                $ward     = DB::table('wards')->where('id', $address->ward_id)->first();

                $ghnDistrictId = $district->ghn_district_id ?? null;
                $ghnWardCode   = $ward->ghn_ward_code ?? null;

                if (! $ghnDistrictId || ! $ghnWardCode) {
                    throw new \Exception("Thiếu mã GHN: district_id={$address->district_id}, ward_id={$address->ward_id}");
                }

                $ghnService = app(\App\Services\GHNService::class);

                $services  = $ghnService->getAvailableServices(1489, (int) $ghnDistrictId);
                $serviceId = $services[0]['service_id'] ?? null;

                if (! $serviceId) {
                    throw new \Exception("Không có dịch vụ GHN phù hợp");
                }
            
                
                $shippingResult = $ghnService->calculateShippingFee(
                    $serviceId,
                    0,
                    1482,
                    (int) $ghnDistrictId,
                    $ghnWardCode,
                    10, 10, $totalWeight * 1000, 10
                );

                $shippingFee = $shippingResult['total'] ?? $shippingFee;

            } catch (\Exception $e) {
                \Log::error("Lỗi GHN khi tạo đơn hàng user_id={$user->id}: " . $e->getMessage());
            }

            $orders[] = [
                'user_id'            => $user->id,
                'address_id'         => $address->id,
                'recipient_name'     => $user->name,
                'recipient_phone'    => $user->phone_number,
                'recipient_address'  => $address->address_line,
                'province_id'        =>1,
                'district_id'        => 1,
                'ward_id'            =>1,
                'total_amount'       => $totalAmount,
                'total_weight'       => $totalWeight,
                'coupon_id'          => 3,
                'payment_method'     => 'cod',
                'status'             => 'processing',
                'shipping_fee'       => $shippingFee,
                'final_total'        => $totalAmount + $shippingFee,
                'shipping_method_id' => 1,
                'shipped_at'         => Carbon::now()->addDays(2),
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
                'deleted_at'         => null,
            ];
        }

        DB::table('orders')->insert($orders);
    }
}