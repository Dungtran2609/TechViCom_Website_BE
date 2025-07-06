@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Sửa đơn hàng #{{ $orderData['id'] }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <a href="{{ route('admin.order.show', $orderData['id']) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Xem
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.order.updateOrders', $orderData['id']) }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <div class="mb-3">
                            <label for="recipient_name" class="form-label">Tên người nhận:</label>
                            <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name', $orderData['recipient_name']) }}" @if ($orderData['status'] !== 'pending') readonly @endif>
                        </div>
                        <div class="mb-3">
                            <label for="recipient_phone" class="form-label">Số điện thoại:</label>
                            <input type="text" name="recipient_phone" class="form-control" value="{{ old('recipient_phone', $orderData['recipient_phone']) }}" @if ($orderData['status'] !== 'pending') readonly @endif>
                        </div>
                        <div class="mb-3">
                            <label for="recipient_address" class="form-label">Địa chỉ giao hàng:</label>
                            <input type="text" name="recipient_address" class="form-control" value="{{ old('recipient_address', $orderData['recipient_address']) }}" @if ($orderData['status'] !== 'pending') readonly @endif>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Phương thức thanh toán:</label>
                            <select name="payment_method" class="form-control" @if ($orderData['status'] !== 'pending') readonly @endif>
                                <option value="" disabled {{ !$orderData['payment_method'] ? 'selected' : '' }}>Chọn phương thức</option>
                                <option value="credit_card" {{ old('payment_method', $orderData['payment_method']) === 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng/ghi nợ</option>
                                <option value="bank_transfer" {{ old('payment_method', $orderData['payment_method']) === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                                <option value="cod" {{ old('payment_method', $orderData['payment_method']) === 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="shipped_at" class="form-label">Ngày giao hàng:</label>
                            <input type="date" name="shipped_at" class="form-control" value="{{ old('shipped_at', $orderData['shipped_at'] ? $orderData['shipped_at']->format('Y-m-d') : '') }}" @if ($orderData['status'] !== 'pending') readonly @endif>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_method_id" class="form-label">Phương thức vận chuyển:</label>
                            <select name="shipping_method_id" class="form-control" @if ($orderData['status'] !== 'pending') readonly @endif>
                                <option value="">Chọn phương thức vận chuyển</option>
                                @foreach ($orderData['shipping_methods'] as $method)
                                    <option value="{{ $method->id }}" {{ old('shipping_method_id', $orderData['shipping_method_id']) == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }} ({{ number_format($method->fee, 2) }} VND)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="coupon_id" class="form-label">Mã giảm giá:</label>
                            <select name="coupon_id" class="form-control" @if ($orderData['status'] !== 'pending') readonly @endif>
                                <option value="">Chưa áp dụng mã giảm giá</option>
                                @foreach ($orderData['coupons'] as $coupon)
                                    <option value="{{ $coupon->id }}" {{ old('coupon_id', $orderData['coupon_id']) == $coupon->id ? 'selected' : '' }}>
                                        {{ $coupon->code }} ({{ $coupon->discount_type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 2) . ' VND' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Chi tiết sản phẩm:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $itemTotalSum = 0;
                                    @endphp
                                    @foreach ($orderData['order_items'] as $index => $item)
                                        @php
                                            $itemTotal = ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                                            $itemTotalSum += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $item['name_product'] }}</td>
                                            <td><input type="number" name="order_items[{{ $index }}][quantity]" class="form-control" value="{{ old("order_items.$index.quantity", $item['quantity']) }}" min="1" @if ($orderData['status'] !== 'pending') readonly @endif></td>
                                            <td><input type="number" step="0.01" name="order_items[{{ $index }}][price]" class="form-control" value="{{ old("order_items.$index.price", $item['price']) }}" min="0" @if ($orderData['status'] !== 'pending') readonly @endif></td>
                                            <td>{{ number_format($itemTotal, 2) }} VND</td>
                                            <input type="hidden" name="order_items[{{ $index }}][id]" value="{{ $item['id'] }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Tổng tiền sản phẩm:</label>
                            <input type="text" name="total_amount" class="form-control" value="{{ number_format($orderData['total_amount'] ?? 0, 2) }} VND" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_fee" class="form-label">Phí vận chuyển:</label>
                            <input type="text" name="shipping_fee" class="form-control" value="{{ number_format($orderData['shipping_fee'] ?? 0, 2) }} VND" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="coupon_discount" class="form-label">Giảm giá coupon:</label>
                            <input type="text" name="coupon_discount" class="form-control" value="{{ number_format($orderData['coupon_discount'] ?? 0, 2) }} VND" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="final_total" class="form-label">Tổng tiền cuối cùng:</label>
                            <input type="text" name="final_total" class="form-control" value="{{ number_format($orderData['final_total'] ?? 0, 2) }} VND" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái:</label>
                            <select name="status" class="form-control" @if (in_array($orderData['status'], ['cancelled', 'returned'])) disabled @endif>
                                <option value="" disabled {{ !old('status') && !in_array($orderData['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'selected' : '' }}>Chọn trạng thái</option>
                                @if ($orderData['status'] === 'pending')
                                    <option value="pending" {{ old('status', $orderData['status']) === 'pending' ? 'selected' : '' }}>Đang chờ xử lý</option>
                                    <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                @elseif ($orderData['status'] === 'processing')
                                    <option value="processing" {{ old('status', $orderData['status']) === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ old('status') === 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                @elseif ($orderData['status'] === 'shipped')
                                    <option value="shipped" {{ old('status', $orderData['status']) === 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                    <option value="delivered" {{ old('status') === 'delivered' ? 'selected' : '' }}>Đã nhận</option>
                                @elseif ($orderData['status'] === 'delivered')
                                    <option value="delivered" {{ old('status', $orderData['status']) === 'delivered' ? 'selected' : '' }}>Đã nhận</option>
                                    <option value="returned" {{ old('status') === 'returned' ? 'selected' : '' }}>Đã trả hàng</option>
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật đơn hàng này?')">Cập nhật</button>
                    </div>
                </form>

                <h5 class="card-title mt-4">Chi tiết sản phẩm</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Thương hiệu</th>
                            <th>Danh mục</th>
                            <th>Tồn kho</th>
                            <th>Biến thể</th>
                            <th>Tên sản phẩm</th>
                            <th>Cân nặng (kg)</th>
                            <th>Kích thước</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemTotalSum = 0;
                        @endphp
                        @foreach ($orderData['order_items'] as $item)
                            @php
                                $itemTotal = ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                                $itemTotalSum += $itemTotal;
                            @endphp
                            <tr>
                                <td>
                                    @if ($item['image_product'])
                                        <img src="{{ asset('storage/' . $item['image_product']) }}" alt="Ảnh sản phẩm" width="40" class="rounded">
                                    @else
                                        <span>Ảnh sản phẩm</span>
                                    @endif
                                </td>
                                <td>{{ $item['brand_name'] }}</td>

                                <td>{{ $item['category_name'] }}</td>
                                <td>{{ $item['stock'] }}</td>
                                @foreach ($item['attributes'] as $attribute)
                        <p>{{ $attribute['name'] }}: {{ $attribute['value'] }}</p>
                        <input type="hidden" name="order_items[{{ $index }}][attributes][{{ $attribute['name'] }}]" value="{{ $attribute['value'] }}">
                    @endforeach
                                <td>{{ $item['name_product'] }}</td>
                                <td>{{ number_format($item['weight'], 2) }}</td>
                                <td>{{ $item['dimensions'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price'], 2) }} VND</td>
                                <td>{{ number_format($itemTotal, 2) }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Tổng tiền sản phẩm (tính toán):</strong> {{ number_format($itemTotalSum, 2) }} VND</p>
                <p><strong>Tổng tiền sản phẩm (lưu trữ):</strong> {{ number_format($orderData['total_amount'] ?? 0, 2) }} VND</p>
                @if (abs($itemTotalSum - ($orderData['total_amount'] ?? 0)) > 0.01)
                    <div class="alert alert-warning">
                        Cảnh báo: Tổng tiền sản phẩm tính toán ({{ number_format($itemTotalSum, 2) }} VND) không khớp với tổng tiền lưu trữ ({{ number_format($orderData['total_amount'] ?? 0, 2) }} VND).
                    </div>
                @endif
                <p><strong>Phí vận chuyển:</strong> {{ number_format($orderData['shipping_fee'] ?? 0, 2) }} VND</p>
                <p><strong>Giảm giá coupon:</strong> {{ number_format($orderData['coupon_discount'] ?? 0, 2) }} VND</p>
                <p><strong>Tổng tiền cuối cùng:</strong> {{ number_format($orderData['final_total'] ?? 0, 2) }} VND</p>
            </div>
        </div>
    </div>
@endsection