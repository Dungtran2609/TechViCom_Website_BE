<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Order::with([
            'user',
            'orderItems.product',
            'orderItems.product.category',
            'orderItems.product.brand', // Thêm quan hệ brand
            'orderItems.productVariant',
            'orderItems.productVariant.productAllImages',
            'orderItems.productVariant.attributeValues',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
            'returns',
        ])
            ->when($request->search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        $orderData = $orders->map(function ($order) {
            $calculatedTotalAmount = $order->orderItems->sum(function ($item) {
                $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return ($item->quantity ?? 0) * $priceToUse;
            });

            $primaryImage = $order->orderItems->flatMap(function ($item) {
                return $item->productVariant->productAllImages->where('is_primary', true);
            })->first();
            $image = $primaryImage ? $primaryImage->img_url : ($order->orderItems->first()?->productVariant?->image ?? null);

            $userName       = $order->user?->name ?? 'Khách vãng lai';
            $productNames   = $order->orderItems->pluck('product.name')->implode(', ');
            $totalQuantity  = $order->orderItems->sum('quantity');
            $shippingFee    = $order->shipping_fee ?? ($order->shippingMethod?->fee ?? 0);
            $couponDiscount = $order->coupon_discount ?? $this->calculateCouponDiscount($order->coupon, $calculatedTotalAmount + $shippingFee);
            $finalTotal     = $calculatedTotalAmount + $shippingFee - $couponDiscount;

            $orderItems = $order->orderItems->map(function ($item) {
                $primaryImage = $item->productVariant->productAllImages->where('is_primary', true)->first();
                $imageUrl     = $primaryImage ? $primaryImage->img_url : $item->productVariant?->image;
                $attributes   = $item->productVariant->attributeValues->map(function ($attributeValue) {
                    return [
                        'name'  => $attributeValue->attribute->name,
                        'value' => $attributeValue->value ?? 'Chưa xác định',
                    ];
                })->unique('name')->values(); // Loại bỏ lặp và giữ giá trị duy nhất
                $priceToDisplay = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;

                return [
                    'image_product' => $imageUrl,
                    'category_name' => $item->product?->category?->name ?? 'Chưa có danh mục',
                    'name_product'  => $item->product?->name ?? 'Sản phẩm không xác định',
                    'brand_name'    => $item->product?->brand?->name ?? 'Chưa có thương hiệu',
                    'weight'        => $item->productVariant?->weight ?? 0,
                    'dimensions'    => $item->productVariant?->dimensions ?? 'N/A',
                    'stock'         => $item->productVariant?->stock ?? 0,
                    'quantity'      => $item->quantity,
                    'price'         => $priceToDisplay,
                    'attributes'    => $attributes,
                    'id'            => $item->id,
                ];
            });

            $paymentMethodMap = [
                'credit_card'   => 'Thẻ tín dụng/ghi nợ',
                'bank_transfer' => 'Chuyển khoản ngân hàng',
                'cod'           => 'Thanh toán khi nhận hàng',
            ];
            $statusMap = [
                'pending'    => 'Đang chờ xử lý',
                'processing' => 'Đang xử lý',
                'shipped'    => 'Đã giao',
                'delivered'  => 'Đã nhận',
                'cancelled'  => 'Đã hủy',
                'returned'   => 'Đã trả hàng',
            ];

            $shippedAt = $order->shipped_at ? Carbon::parse($order->shipped_at) : null;

            return [
                'id'                        => $order->id,
                'image'                     => $image,
                'user_name'                 => $userName,
                'product_names'             => $productNames,
                'total_quantity'            => $totalQuantity,
                'subtotal'                  => $calculatedTotalAmount,
                'shipping_fee'              => $shippingFee,
                'coupon_discount'           => $couponDiscount,
                'final_total'               => $finalTotal,
                'status'                    => $order->status,
                'status_vietnamese'         => $statusMap[$order->status] ?? $order->status,
                'created_at'                => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
                'payment_method'            => $order->payment_method,
                'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
                'recipient_name'            => $order->recipient_name,
                'recipient_phone'           => $order->recipient_phone,
                'recipient_address'         => $order->recipient_address,
                'shipped_at'                => $shippedAt,
                'order_items'               => $orderItems,
                'shipping_method_name'      => $order->shippingMethod?->name ?? 'Chưa chọn',
                'coupon_code'               => $order->coupon?->code ?? 'Chưa áp dụng',
                'has_return_request'        => $order->returns()->where('status', 'pending')->exists(),
            ];
        });

        return view('admin.oders.listOrders', [
            'orders'     => $orderData,
            'pagination' => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product',
            'orderItems.product.category',
            'orderItems.product.brand', // Thêm quan hệ brand

            'orderItems.productVariant',
            'orderItems.productVariant.productAllImages',
            'orderItems.productVariant.attributeValues',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
            'address',
            'returns',
        ])->findOrFail($id);

        $calculatedTotalAmount = $order->orderItems->sum(function ($item) {
            $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
            return ($item->quantity ?? 0) * $priceToUse;
        });

        $shippingFee    = $order->shipping_fee ?? ($order->shippingMethod?->fee ?? 0);
        $couponDiscount = $order->coupon_discount ?? $this->calculateCouponDiscount($order->coupon, $calculatedTotalAmount + $shippingFee);
        $finalTotal     = $calculatedTotalAmount + $shippingFee - $couponDiscount;

        $paymentMethodMap = [
            'credit_card'   => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod'           => 'Thanh toán khi nhận hàng',
        ];

        $statusMap = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đã giao',
            'delivered'  => 'Đã nhận',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Đã trả hàng',
        ];

        $shippedAt = $order->shipped_at ? Carbon::parse($order->shipped_at) : null;

        $orderData = [
            'id'                        => $order->id,
            'user_name'                 => $order->user?->name ?? 'Khách vãng lai',
            'total_quantity'            => $order->orderItems->sum('quantity'),
            'subtotal'                  => $calculatedTotalAmount,
            'shipping_fee'              => $shippingFee,
            'coupon_discount'           => $couponDiscount,
            'final_total'               => $finalTotal,
            'total_amount'              => $calculatedTotalAmount,
            'status'                    => $order->status,
            'status_vietnamese'         => $statusMap[$order->status] ?? $order->status,
            'created_at'                => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            'payment_method'            => $order->payment_method,
            'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
            'recipient_name'            => $order->recipient_name,
            'recipient_phone'           => $order->recipient_phone,
            'recipient_address'         => $order->recipient_address,
            'shipped_at'                => $shippedAt,
            'order_items'               => $order->orderItems->map(function ($item) {
                $primaryImage = $item->productVariant->productAllImages->where('is_primary', true)->first();
                $imageUrl     = $primaryImage ? $primaryImage->img_url : $item->productVariant?->image;
                $attributes   = $item->productVariant->attributeValues->map(function ($attributeValue) {
                    return [
                        'name'  => $attributeValue->attribute->name,
                       'value' => $attributeValue->value ?? 'Chưa xác định',
                    ];
                })->unique('name')->values(); // Loại bỏ lặp
                $priceToDisplay = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return [
                    'image_product' => $imageUrl,
                    'category_name' => $item->product?->category?->name ?? 'Chưa có danh mục',
                    'brand_name'    => $item->product?->brand?->name ?? 'Chưa có thương hiệu',

                    'name_product'  => $item->product?->name ?? 'Sản phẩm không xác định',
                    'weight'        => $item->productVariant?->weight ?? 0,
                    'dimensions'    => $item->productVariant?->dimensions ?? 'N/A',
                    'stock'         => $item->productVariant?->stock ?? 0,
                    'quantity'      => $item->quantity,
                    'price'         => $priceToDisplay,
                    'attributes'    => $attributes,
                    'id'            => $item->id,
                ];
            }),
            'address'                   => $order->address,
            'return_requests'           => $order->returns->all(),
            'shipping_method_name'      => $order->shippingMethod?->name ?? 'Chưa chọn',
            'coupon_code'               => $order->coupon?->code ?? 'Chưa áp dụng',
        ];

        return view('admin.oders.showOrders', compact('orderData'));
    }
    public function edit($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product',
            'orderItems.product.category',
            'orderItems.product.brand', // Thêm quan hệ brand

            'orderItems.productVariant',
            'orderItems.productVariant.productAllImages',
            'orderItems.productVariant.attributeValues',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
            'address',
        ])->findOrFail($id);

        $shippingMethods = ShippingMethod::all();
        $coupons         = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        $calculatedTotalAmount = $order->orderItems->sum(function ($item) {
            $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
            return ($item->quantity ?? 0) * $priceToUse;
        });

        $shippingFee    = $order->shipping_fee ?? ($order->shippingMethod?->fee ?? 0);
        $couponDiscount = $order->coupon_discount ?? $this->calculateCouponDiscount($order->coupon, $calculatedTotalAmount + $shippingFee);
        $finalTotal     = $calculatedTotalAmount + $shippingFee - $couponDiscount;

        $paymentMethodMap = [
            'credit_card'   => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod'           => 'Thanh toán khi nhận hàng',
        ];

        $statusMap = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đã giao',
            'delivered'  => 'Đã nhận',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Đã trả hàng',
        ];

        $shippedAt = $order->shipped_at ? Carbon::parse($order->shipped_at) : null;

        $orderData = [
            'id'                        => $order->id,
            'user_name'                 => $order->user?->name ?? 'Khách vãng lai',
            'total_quantity'            => $order->orderItems->sum('quantity'),
            'subtotal'                  => $calculatedTotalAmount,
            'shipping_fee'              => $shippingFee,
            'coupon_discount'           => $couponDiscount,
            'final_total'               => $finalTotal,
            'total_amount'              => $calculatedTotalAmount,
            'status'                    => $order->status,
            'status_vietnamese'         => $statusMap[$order->status] ?? $order->status,
            'created_at'                => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            'payment_method'            => $order->payment_method,
            'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
            'recipient_name'            => $order->recipient_name,
            'recipient_phone'           => $order->recipient_phone,
            'recipient_address'         => $order->recipient_address,
            'shipped_at'                => $shippedAt,
            'order_items'               => $order->orderItems->map(function ($item) {
                $primaryImage = $item->productVariant->productAllImages->where('is_primary', true)->first();
                $imageUrl     = $primaryImage ? $primaryImage->img_url : $item->productVariant?->image;
                $attributes   = $item->productVariant->attributeValues->map(function ($attributeValue) {
                    return [
                        'name'  => $attributeValue->attribute->name,
                        'value' => $attributeValue->value ?? 'Chưa xác định',
                    ];
                })->unique('name')->values(); // Loại bỏ lặp
                $priceToDisplay = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return [
                    'image_product' => $imageUrl,
                    'category_name' => $item->product?->category?->name ?? 'Chưa có danh mục',
                    'brand_name'    => $item->product?->brand?->name ?? 'Chưa có thương hiệu',

                    'name_product'  => $item->product?->name ?? 'Sản phẩm không xác định',
                    'weight'        => $item->productVariant?->weight ?? 0,
                    'dimensions'    => $item->productVariant?->dimensions ?? 'N/A',
                    'stock'         => $item->productVariant?->stock ?? 0,
                    'quantity'      => $item->quantity,
                    'price'         => $priceToDisplay,
                    'attributes'    => $attributes,
                    'id'            => $item->id,
                ];
            }),
            'address'                   => $order->address,
            'shipping_methods'          => $shippingMethods,
            'coupons'                   => $coupons,
            'shipping_method_id'        => $order->shipping_method_id,
            'coupon_id'                 => $order->coupon_id,
        ];

        return view('admin.oders.editOrders', compact('orderData'));
    }
    public function updateOrders(Request $request, $id)
    {
        $order         = Order::findOrFail($id);
        $currentStatus = $order->status;

        $data = $request->only([
            'status',
            'recipient_name',
            'recipient_phone',
            'recipient_address',
            'payment_method',
            'shipped_at',
            'order_items',
            'shipping_method_id',
            'coupon_id',
        ]);

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
        if (isset($data['status']) && ! in_array($data['status'], $validStatuses)) {
            return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $validPaymentMethods = ['credit_card', 'bank_transfer', 'cod'];
        if (isset($data['payment_method']) && ! in_array($data['payment_method'], $validPaymentMethods)) {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        // Chuyển đổi shipped_at từ chuỗi thành Carbon nếu có
        if (isset($data['shipped_at']) && $data['shipped_at']) {
            $data['shipped_at'] = Carbon::parse($data['shipped_at']);
        }

        $statusTransitions = [
            'pending'    => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped'    => ['delivered', 'cancelled'],
            'delivered'  => ['returned'],
            'cancelled'  => [],
            'returned'   => [],
        ];

        $editableFields = [];
        switch ($currentStatus) {
            case 'pending':
                $editableFields = ['recipient_name', 'recipient_phone', 'recipient_address', 'payment_method', 'status', 'order_items', 'shipped_at', 'shipping_method_id', 'coupon_id'];
                if (isset($data['status']) && ! in_array($data['status'], $statusTransitions['pending'])) {
                    return redirect()->back()->with('error', 'Chỉ có thể chuyển trạng thái từ Đang chờ xử lý sang Đang xử lý hoặc Đã hủy.');
                }
                break;
            case 'processing':
                $editableFields = ['status'];
                if (isset($data['status']) && ! in_array($data['status'], $statusTransitions['processing'])) {
                    return redirect()->back()->with('error', 'Chỉ có thể chuyển trạng thái từ Đang xử lý sang Đã giao hoặc Đã hủy.');
                }
                if (isset($data['payment_method']) && $data['payment_method'] !== $order->payment_method) {
                    return redirect()->back()->with('error', 'Không thể thay đổi phương thức thanh toán khi đơn hàng đã ' . ($statusMap[$currentStatus] ?? $currentStatus) . '.');
                }
                break;
            case 'shipped':
                $editableFields = ['status'];
                if (isset($data['status']) && ! in_array($data['status'], $statusTransitions['shipped'])) {
                    return redirect()->back()->with('error', 'Chỉ có thể chuyển trạng thái từ Đã giao sang Đã nhận hoặc Đã hủy.');
                }
                if (isset($data['payment_method']) && $data['payment_method'] !== $order->payment_method) {
                    return redirect()->back()->with('error', 'Không thể thay đổi phương thức thanh toán khi đơn hàng đã ' . ($statusMap[$currentStatus] ?? $currentStatus) . '.');
                }
                break;
            case 'delivered':
                $editableFields = ['status'];
                if (isset($data['status']) && ! in_array($data['status'], $statusTransitions['delivered'])) {
                    return redirect()->back()->with('error', 'Chỉ có thể chuyển trạng thái từ Đã nhận sang Đã trả.');
                }
                if (isset($data['payment_method']) && $data['payment_method'] !== $order->payment_method) {
                    return redirect()->back()->with('error', 'Không thể thay đổi phương thức thanh toán khi đơn hàng đã ' . ($statusMap[$currentStatus] ?? $currentStatus) . '.');
                }
                break;
            case 'cancelled':
            case 'returned':
                $editableFields = [];
                if (isset($data['status'])) {
                    return redirect()->back()->with('error', 'Không thể thay đổi trạng thái của đơn hàng đã ' . ($statusMap[$currentStatus] ?? $currentStatus) . '.');
                }
                if (isset($data['payment_method']) && $data['payment_method'] !== $order->payment_method) {
                    return redirect()->back()->with('error', 'Không thể thay đổi phương thức thanh toán khi đơn hàng đã ' . ($statusMap[$currentStatus] ?? $currentStatus) . '.');
                }
                break;
        }

        // Xác định trạng thái thanh toán để kiểm soát payment_method
        $isPaid = in_array($currentStatus, ['delivered', 'cancelled', 'returned']);
        if ($isPaid && isset($data['payment_method'])) {
            unset($data['payment_method']);
        }

        // Validate dữ liệu
        $validator = Validator::make($data, [
            'recipient_name'         => 'nullable|string|max:255',
            'recipient_phone'        => 'nullable|string|max:20',
            'recipient_address'      => 'nullable|string|max:500',
            'payment_method'         => 'nullable|in:' . implode(',', $validPaymentMethods),
            'shipped_at'             => 'nullable|date',
            'order_items'            => 'nullable|array',
            'order_items.*.quantity' => 'nullable|integer|min:1',
            'order_items.*.price'    => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:' . implode(',', $validStatuses),
            'shipping_method_id'     => 'nullable|exists:shipping_methods,id',
            'coupon_id'              => 'nullable|exists:coupons,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tính lại total_amount (tổng tiền sản phẩm)
        $totalAmount = 0;
        if (isset($data['order_items']) && is_array($data['order_items'])) {
            foreach ($order->orderItems as $item) {
                $itemData = collect($data['order_items'])->firstWhere('id', $item->id);
                $quantity = $itemData['quantity'] ?? $item->quantity;
                $price    = $itemData['price'] ?? ($item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0);
                $totalAmount += $quantity * $price;
            }
        } else {
            $totalAmount = $order->orderItems->sum(function ($item) {
                $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return ($item->quantity ?? 0) * $priceToUse;
            });
        }

        // Lấy shipping fee từ shipping_method_id (không lưu vào DB)
        $shippingFee = 0;
        if (isset($data['shipping_method_id'])) {
            $shippingMethod = ShippingMethod::find($data['shipping_method_id']);
            if ($shippingMethod) {
                $shippingFee               = $shippingMethod->fee;
                $order->shipping_method_id = $shippingMethod->id; // Cập nhật khóa ngoại
            }
        } elseif ($order->shippingMethod) {
            $shippingFee = $order->shippingMethod->fee;
        }

        // Lấy coupon discount từ coupon_id (không lưu vào DB)
        $couponDiscount = 0;
        if (isset($data['coupon_id'])) {
            $coupon = Coupon::find($data['coupon_id']);
            if ($coupon && $coupon->status && now()->between($coupon->start_date, $coupon->end_date)) {
                $couponDiscount   = $this->calculateCouponDiscount($coupon, $totalAmount + $shippingFee);
                $order->coupon_id = $coupon->id; // Cập nhật khóa ngoại
            }
        } elseif ($order->coupon) {
            $couponDiscount = $this->calculateCouponDiscount($order->coupon, $totalAmount + $shippingFee);
        }

        // Tính lại final_total
        $finalTotal = $totalAmount + $shippingFee - $couponDiscount;

        // Cập nhật thông tin đơn hàng
        $fieldsToUpdate = array_intersect(array_keys($data), $editableFields);
        if (! empty($fieldsToUpdate) || ! empty($data['order_items']) || isset($data['shipping_method_id']) || isset($data['coupon_id'])) {
            $order->fill(array_intersect_key($data, array_flip($editableFields)));
        }

        // Cập nhật trạng thái nếu có
        if (in_array('status', $editableFields) && isset($data['status']) && in_array($data['status'], $statusTransitions[$currentStatus] ?? [])) {
            $order->status = $data['status'];
        }

        // Cập nhật order_items nếu có
        if (isset($data['order_items']) && in_array('order_items', $editableFields)) {
            foreach ($data['order_items'] as $itemData) {
                $orderItem = $order->orderItems()->find($itemData['id']);
                if ($orderItem) {
                    $orderItem->update([
                        'quantity' => $itemData['quantity'] ?? $orderItem->quantity,
                        'price'    => $itemData['price'] ?? $orderItem->price,
                    ]);
                }
            }
        }

                                             // Chỉ cập nhật total_amount và final_total, không sử dụng shipping_fee và coupon_discount
        $order->total_amount = $totalAmount; // Cập nhật total_amount
        $order->final_total  = $finalTotal;  // Cập nhật final_total
        $order->save();

        $statusMap = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đã giao',
            'delivered'  => 'Đã nhận',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Đã trả hàng',
        ];

        $message = 'Đơn hàng đã được cập nhật.';
        if (isset($data['status'])) {
            $message .= ' Trạng thái đã chuyển sang ' . ($statusMap[$data['status']] ?? $data['status']) . '.';
        }
        return redirect()->route('admin.order.show', $id)->with('success', $message);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Chỉ cho phép xóa mềm khi trạng thái là cancelled, returned, hoặc delivered
            $allowedStatuses = ['cancelled', 'returned', 'delivered'];
            if (! in_array($order->status, $allowedStatuses)) {
                return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng khi trạng thái là Đã hủy, Đã trả hàng, hoặc Đã nhận.');
            }

            // Thực hiện xóa mềm
            $order->delete();

            return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $trashedOrders = Order::onlyTrashed()->with([
            'user',
            'orderItems.product',
            'orderItems.productVariant',
            'orderItems.productVariant.productAllImages',
        ])->latest()->get();

        $orderData = $trashedOrders->map(function ($order) {
            $calculatedTotalAmount = $order->orderItems->sum(function ($item) {
                $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return ($item->quantity ?? 0) * $priceToUse;
            });

            $primaryImage = $order->orderItems->flatMap(function ($item) {
                return $item->productVariant->productAllImages->where('is_primary', true);
            })->first();
            $image = $primaryImage ? $primaryImage->img_url : ($order->orderItems->first()?->productVariant?->image ?? null);

            $userName       = $order->user?->name ?? 'Khách vãng lai';
            $productNames   = $order->orderItems->pluck('product.name')->implode(', ');
            $totalQuantity  = $order->orderItems->sum('quantity');
            $shippingFee    = $order->shipping_fee ?? 0;
            $couponDiscount = $order->coupon_discount ?? 0;
            $finalTotal     = $calculatedTotalAmount + $shippingFee - $couponDiscount;

            $paymentMethodMap = [
                'credit_card'   => 'Thẻ tín dụng/ghi nợ',
                'bank_transfer' => 'Chuyển khoản ngân hàng',
                'cod'           => 'Thanh toán khi nhận hàng',
            ];
            $statusMap = [
                'pending'    => 'Đang chờ xử lý',
                'processing' => 'Đang xử lý',
                'shipped'    => 'Đã giao',
                'delivered'  => 'Đã nhận',
                'cancelled'  => 'Đã hủy',
                'returned'   => 'Đã trả hàng',
            ];

            $shippedAt = $order->shipped_at ? Carbon::parse($order->shipped_at) : null;
            $deletedAt = $order->deleted_at ? Carbon::parse($order->deleted_at)->format('d/m/Y H:i') : null;

            return [
                'id'                        => $order->id,
                'image'                     => $image,
                'user_name'                 => $userName,
                'product_names'             => $productNames,
                'total_quantity'            => $totalQuantity,
                'subtotal'                  => $calculatedTotalAmount,
                'shipping_fee'              => $shippingFee,
                'coupon_discount'           => $couponDiscount,
                'final_total'               => $finalTotal,
                'status'                    => $order->status,
                'status_vietnamese'         => $statusMap[$order->status] ?? $order->status,
                'created_at'                => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
                'payment_method'            => $order->payment_method,
                'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
                'recipient_name'            => $order->recipient_name,
                'recipient_phone'           => $order->recipient_phone,
                'recipient_address'         => $order->recipient_address,
                'shipped_at'                => $shippedAt,
                'deleted_at'                => $deletedAt,
            ];
        });

        return view('admin.order.trashed', ['orders' => $orderData]);
    }

    public function restore($id)
    {
        try {
            $order = Order::withTrashed()->findOrFail($id);
            $order->restore();

            return redirect()->route('admin.order.trashed')->with('success', 'Đơn hàng đã được phục hồi thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi phục hồi đơn hàng: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::withTrashed()->findOrFail($id);
            $order->orderItems()->forceDelete(); // Xóa vĩnh viễn order_items
            $order->forceDelete();               // Xóa vĩnh viễn order

            DB::commit();

            return redirect()->route('admin.order.trashed')->with('success', 'Đơn hàng đã được xóa vĩnh viễn.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn đơn hàng: ' . $e->getMessage());
        }
    }
    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user', 'order.orderItems.product', 'order.orderItems.productVariant'])
            ->latest()
            ->paginate(15);

        $returnData = $returns->map(function ($return) {
            $order            = $return->order;
            $paymentMethodMap = [
                'credit_card'   => 'Thẻ tín dụng/ghi nợ',
                'bank_transfer' => 'Chuyển khoản ngân hàng',
                'cod'           => 'Thanh toán khi nhận hàng',
            ];
            $statusMap = [
                'pending'    => 'Đang chờ xử lý',
                'approved'   => 'Đã phê duyệt',
                'rejected'   => 'Đã từ chối',
                'processing' => 'Đang xử lý',
                'shipped'    => 'Đã giao',
                'delivered'  => 'Đã nhận',
                'cancelled'  => 'Đã hủy',
                'returned'   => 'Đã trả hàng',
            ];

            $reason = $return->reason ?: ($return->type === 'cancel' ? 'Khách hàng muốn hủy đơn hàng' : 'Khách hàng muốn đổi/tra hàng');

            // Tính order_total động dựa trên orderItems
            $calculatedTotalAmount = $order->orderItems->sum(function ($item) {
                $priceToUse = $item->productVariant?->sale_price ?? $item->productVariant?->price ?? 0;
                return ($item->quantity ?? 0) * $priceToUse;
            });
            $shippingFee    = $order->shippingMethod?->fee ?? 0;
            $couponDiscount = $order->coupon ? $this->calculateCouponDiscount($order->coupon, $calculatedTotalAmount + $shippingFee) : 0;
            $orderTotal     = $calculatedTotalAmount + $shippingFee - $couponDiscount;

            return [
                'id'                        => $return->id,
                'order_id'                  => $order->id,
                'user_name'                 => $order->user?->name ?? 'Khách vãng lai',
                'reason'                    => $reason,
                'type'                      => $return->type,
                'status'                    => $return->status,
                'status_vietnamese'         => $statusMap[$return->status] ?? $return->status,
                'requested_at'              => Carbon::parse($return->requested_at)->format('d/m/Y H:i'),
                'processed_at'              => $return->processed_at ? Carbon::parse($return->processed_at)->format('d/m/Y H:i') : null,
                'admin_note'                => $return->admin_note,
                'order_total'               => $orderTotal,
                'order_status'              => $order->status,
                'order_status_vietnamese'   => $statusMap[$order->status] ?? $order->status,
                'payment_method'            => $order->payment_method,
                'payment_method_vietnamese' => $paymentMethodMap[$order->payment_method] ?? $order->payment_method,
            ];
        });

        return view('admin.oders.returns', [
            'returns'    => $returnData,
            'pagination' => $returns,
        ]);
    }

    public function processReturn(Request $request, $id)
    {
        $return    = OrderReturn::findOrFail($id);
        $action    = $request->input('action'); // 'approve' hoặc 'reject'
        $adminNote = $request->input('admin_note');

        if (! in_array($action, ['approve', 'reject'])) {
            return redirect()->back()->with('error', 'Hành động không hợp lệ.');
        }

        $return->status       = ($action === 'approve') ? 'approved' : 'rejected';
        $return->processed_at = now();
        $return->admin_note   = $adminNote;

        // Kiểm tra điều kiện trước khi cập nhật trạng thái đơn hàng
        if ($action === 'approve') {
            if ($return->type === 'cancel' && $return->order->status !== 'pending') {
                return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng khi trạng thái là Đang chờ xử lý.');
            }
            if ($return->type === 'return' && $return->order->status !== 'delivered') {
                return redirect()->back()->with('error', 'Chỉ có thể trả hàng khi trạng thái là Đã nhận.');
            }

            if ($return->type === 'cancel' && $return->order->status === 'pending') {
                $return->order->status = 'cancelled';
                $return->order->save();
            } elseif ($return->type === 'return' && $return->order->status === 'delivered') {
                $return->order->status = 'returned';
                $return->order->save();
            } else {
                return redirect()->back()->with('error', 'Yêu cầu không hợp lệ dựa trên trạng thái hiện tại.');
            }
        }

        $return->save();

        $statusMap = [
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Đã từ chối',
        ];
        $message = 'Yêu cầu ' . ($return->type === 'cancel' ? 'hủy' : 'đổi/tra') . ' đã được ' . $statusMap[$return->status] . '.';

        return redirect()->route('admin.order.returns')->with('success', $message);
    }
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (! $coupon || ! $coupon->status || now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) {
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
