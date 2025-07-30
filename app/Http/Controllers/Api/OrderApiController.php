<?php
namespace App\Http\Controllers\Api;

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

class OrderApiController extends Controller
{
    public function index()
    {
        return response()->json(Order::all());
    }

    public function show(Order $order)
    {
        return response()->json($order);
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
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng',
            ], 404);
        }

        try {
            $order->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đơn hàng đã được xóa mềm',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi khi xóa đơn hàng',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

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

    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'payment_method' => 'required|in:cod,bank_transfer,credit_card',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'coupon_code' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = null;
                $price = $product->price;

                if (isset($item['variant_id'])) {
                    $variant = ProductVariant::findOrFail($item['variant_id']);
                    $price = $variant->sale_price ?? $variant->price;
                }

                $subtotal = $price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'total_amount' => $totalAmount,
                'shipping_fee' => 0,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'payment_method' => $request->payment_method,
                'shipping_method_id' => $request->shipping_method_id,
                'status' => 'pending',
                'note' => $request->note,
            ]);

            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order' => $order->load(['orderItems.product', 'orderItems.productVariant']),
                    'shipping_calculation_needed' => true,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt hàng',
                'error' => $e->getMessage()
            ], 500);
        }
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
