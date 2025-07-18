@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Chi tiết đơn hàng #{{ $orderData['id'] }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <a href="{{ route('admin.order.edit', $orderData['id']) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Thông tin đơn hàng -->
                <h5 class="card-title">Thông tin đơn hàng</h5>
                <div class="row">
                    <div class="col-md-6">
    <p><strong>Tên người nhận:</strong> {{ $orderData['recipient_name'] }}</p>
    <p><strong>Số điện thoại:</strong> {{ $orderData['recipient_phone'] }}</p>
    <p><strong>Địa chỉ giao hàng:</strong> {{ $orderData['recipient_address'] }}</p>

    {{-- Thêm hiển thị tỉnh, quận, phường --}}
    <div class="row">
        <div class="col-4">
            <strong>Tỉnh:</strong>
            {{ $orderData['province_name'] ?? ($orderData['province_id'] ?? 'Chưa có') }}
        </div>
        <div class="col-4">
            <strong>Quận:</strong>
            {{ $orderData['district_name'] ?? ($orderData['district_id'] ?? 'Chưa có') }}
        </div>
        <div class="col-4">
            <strong>Phường:</strong>
            {{ $orderData['ward_name'] ?? ($orderData['ward_id'] ?? 'Chưa có') }}
        </div>
    </div>

    <p><strong>Phí vận chuyển:</strong> {{ number_format($orderData['shipping_fee'] ?? 0, 2) }} VND</p>
    <p><strong>Phương thức vận chuyển:</strong> {{ $orderData['shipping_method_name'] ?? 'Chưa chọn' }}</p>
    <p><strong>Giảm giá coupon:</strong> {{ number_format($orderData['coupon_discount'] ?? 0, 2) }} VND</p>
    <p><strong>Mã giảm giá:</strong> {{ $orderData['coupon_code'] ?? 'Chưa áp dụng' }}</p>
    <p><strong>Tổng tiền cuối cùng:</strong> {{ number_format($orderData['final_total'] ?? 0, 2) }} VND</p>
</div>

                </div>

                <!-- Chi tiết sản phẩm -->
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
                                <td>@foreach ($item['attributes'] as $attribute)
                        {{ $attribute['name'] }}: {{ $attribute['value'] }}<br>
                    @endforeach</td>
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
                <p><strong>Tổng tiền sản phẩm (lưu trữ):</strong> {{ number_format($orderData['subtotal'] ?? 0, 2) }} VND</p>
                <p><strong>Phí vận chuyển:</strong> {{ number_format($orderData['shipping_fee'] ?? 0, 2) }} VND</p>
                <p><strong>Phương thức vận chuyển:</strong> {{ $orderData['shipping_method_name'] ?? 'Chưa chọn' }}</p>
                <p><strong>Giảm giá coupon:</strong> {{ number_format($orderData['coupon_discount'] ?? 0, 2) }} VND</p>
                <p><strong>Mã giảm giá:</strong> {{ $orderData['coupon_code'] ?? 'Chưa áp dụng' }}</p>
                <p><strong>Tổng tiền cuối cùng:</strong> {{ number_format($orderData['final_total'] ?? 0, 2) }} VND</p>

                @if (abs($itemTotalSum - ($orderData['subtotal'] ?? 0)) > 0.01)
                    <div class="alert alert-warning">
                        Cảnh báo: Tổng tiền sản phẩm tính toán ({{ number_format($itemTotalSum, 2) }} VND) không khớp với tổng tiền lưu trữ ({{ number_format($orderData['subtotal'] ?? 0, 2) }} VND).
                    </div>
                @endif
                @if (abs(($orderData['final_total'] ?? 0) - (($orderData['subtotal'] ?? 0) + ($orderData['shipping_fee'] ?? 0) - ($orderData['coupon_discount'] ?? 0))) > 0.01)
                    <div class="alert alert-warning">
                        Cảnh báo: Tổng tiền cuối cùng ({{ number_format($orderData['final_total'] ?? 0, 2) }} VND) không khớp với công thức ({{ number_format(($orderData['subtotal'] ?? 0) + ($orderData['shipping_fee'] ?? 0) - ($orderData['coupon_discount'] ?? 0), 2) }} VND).
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection