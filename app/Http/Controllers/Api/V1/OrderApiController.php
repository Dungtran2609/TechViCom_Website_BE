<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;

use Illuminate\Support\Str;
class OrderApiController extends Controller
{
    // Thêm đơn hàng 
    public function store(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'province_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'ward_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'status' => 'nullable|string',
            'recipient_name' => 'nullable|string',
            'recipient_phone' => 'nullable|string',
            'recipient_address' => 'nullable|string',
            'shipping_method_id' => 'nullable|integer',
            'coupon_id' => 'nullable|integer|exists:coupons,id',
            'order_items' => 'required|array',
            'order_items.*.variant_id' => 'required|integer|exists:product_variants,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        $orderItems = $request->input('order_items', []);

        if (empty($orderItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Danh sách sản phẩm trống.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($orderItems as $item) {
                $variant = \App\Models\ProductVariant::with('product')->find($item['variant_id']);

                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại.',
                    ], 404);
                }

                $price = $variant->sale_price ?? $variant->price ?? 0;
                $quantity = $item['quantity'];
                $totalAmount += $price * $quantity;

                $orderItemsData[] = [
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // Tính phí vận chuyển
            $provinceName = DB::table('provinces')->where('id', $validated['province_id'])->value('name');
            $shippingFee = 0;
            if ($provinceName === 'Hà Nội') {
                $shippingFee = $totalAmount >= 3000000 ? 0 : 60000;
            } else {
                $shippingFee = $validated['shipping_fee'] ?? 60000;
            }

            // Tính giảm giá
            $couponDiscount = 0;
            $coupon = null;
            if (!empty($validated['coupon_id'])) {
                $coupon = Coupon::find($validated['coupon_id']);
                if ($coupon && $coupon->status && now()->between($coupon->start_date, $coupon->end_date)) {
                    $couponDiscount = $this->calculateCouponDiscount($coupon, $totalAmount);
                }
            }

            $finalTotal = $totalAmount + $shippingFee - $couponDiscount;

            $order = Order::create([
                'user_id' => auth()->id(),
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_address' => $validated['recipient_address'],
                'address_id' => $request->address_id ?? null,
                'province_id' => $validated['province_id'],
                'district_id' => $validated['district_id'],
                'ward_id' => $validated['ward_id'],
                'payment_method' => $validated['payment_method'],
                'shipping_method_id' => $validated['shipping_method_id'],
                'coupon_id' => $coupon?->id ?? null,
                'coupon_discount' => $couponDiscount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'final_total' => $finalTotal,
                'status' => $validated['status'] ?? 'pending',
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo đơn hàng thành công.',
                'data' => $order->load(['orderItems.product', 'orderItems.productVariant']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo đơn hàng.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'province_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'ward_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'status' => 'nullable|string',
            'recipient_name' => 'nullable|string',
            'recipient_phone' => 'nullable|string',
            'recipient_address' => 'nullable|string',
            'shipping_method_id' => 'nullable|integer',
            'coupon_id' => 'nullable|integer|exists:coupons,id',
        ]);

        $order->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'data' => $order,
        ]);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $allowedStatuses = ['cancelled', 'returned', 'delivered'];

            if (!in_array($order->status, $allowedStatuses)) {
                return response()->json([
                    'error' => 'Chỉ có thể xóa đơn hàng khi trạng thái là Đã hủy, Đã trả hàng hoặc Đã giao.'
                ], 400);
            }

            $order->delete();

            return response()->json(['message' => 'Đơn hàng đã được xóa mềm.']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
// List SP
    public function apiUserOrders(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = Order::with(['orderItems.product', 'orderItems.productVariant', 'shippingMethod'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }
// Show 1 SP
    public function apiShow(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $order = Order::with([
            'orderItems.product',
            'orderItems.productVariant',
            'shippingMethod',
            'coupon'
        ])->where('user_id', $user->id)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }


    public function trashed()
    {
        $orders = Order::onlyTrashed()->get();
        return response()->json($orders);
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        if (!$order->trashed()) {
            return response()->json(['message' => 'Đơn hàng chưa bị xóa.'], 400);
        }

        $order->restore();

        return response()->json([
            'message' => 'Khôi phục đơn hàng thành công.',
            'data' => $order,
        ]);
    }

    public function forceDelete($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        $order->forceDelete();

        return response()->json([
            'message' => 'Xóa vĩnh viễn đơn hàng thành công.',
        ]);
    }

    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user', 'order.orderItems.product', 'order.orderItems.productVariant', 'order.shippingMethod', 'order.coupon'])
            ->latest()
            ->paginate(15);

        $paymentMethodMap = [
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod' => 'Thanh toán khi nhận hàng',
        ];

        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Đã từ chối',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã giao',
            'delivered' => 'Đã nhận',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng',
        ];

        $data = $returns->map(function ($return) use ($paymentMethodMap, $statusMap) {
            $order = $return->order;

            $reason = $return->reason ?? ($return->type === 'cancel'
                ? 'Khách hàng muốn hủy đơn hàng'
                : 'Khách hàng muốn đổi/trả hàng');

            $productTotal = $order->orderItems->sum(function ($item) {
                $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                return $price * $item->quantity;
            });

            $shippingFee = $order->shippingMethod->fee ?? 0;
            $couponDiscount = $order->coupon ? $this->calculateCouponDiscount($order->coupon, $productTotal + $shippingFee) : 0;
            $finalTotal = $productTotal + $shippingFee - $couponDiscount;

            return [
                'id' => $return->id,
                'order_id' => $order->id,
                'user_name' => $order->user->name ?? 'Khách vãng lai',
                'reason' => $reason,
                'type' => $return->type,
                'status' => $return->status,
                'status_vietnamese' => $statusMap[$return->status] ?? $return->status,
                'requested_at' => $return->requested_at?->format('d/m/Y H:i'),
                'processed_at' => $return->processed_at?->format('d/m/Y H:i'),
                'admin_note' => $return->admin_note,
                'order_total' => $finalTotal,
                'order_status' => $order->status,
                'order_status_vietnamese' => $statusMap[$order->status] ?? $order->status,
                'payment_method' => $order->payment_method,
                'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $returns->currentPage(),
                'last_page' => $returns->lastPage(),
                'per_page' => $returns->perPage(),
                'total' => $returns->total(),
            ],
        ]);
    }

    public function processReturn(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string',
        ]);

        $return = OrderReturn::with('order')->findOrFail($id);
        $order = $return->order;

        if ($request->action === 'approve') {
            if ($return->type === 'cancel') {
                if ($order->status !== 'pending') {
                    return response()->json(['message' => 'Chỉ có thể hủy đơn khi đang chờ xử lý.'], 422);
                }
                $order->status = 'cancelled';
                $order->save();
            }

            if ($return->type === 'return') {
                if ($order->status !== 'delivered') {
                    return response()->json(['message' => 'Chỉ trả hàng khi đơn đã nhận.'], 422);
                }
                $order->status = 'returned';
                $order->save();
            }

            $return->status = 'approved';
        } else {
            $return->status = 'rejected';
        }

        $return->processed_at = now();
        $return->admin_note = $request->admin_note;
        $return->save();

        return response()->json([
            'message' => 'Xử lý thành công.',
            'data' => [
                'order' => $order,
                'order_return' => $return,
            ],
        ]);
    }

    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (!$coupon || !$coupon->status || now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) {
            return 0;
        }
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($orderTotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->value;
        }
        if ($coupon->min_order_value && $orderTotal < $coupon->min_order_value) {
            return 0;
        }
        if ($coupon->max_order_value && $orderTotal > $coupon->max_order_value) {
            return 0;
        }
        return $discount;
    }
}