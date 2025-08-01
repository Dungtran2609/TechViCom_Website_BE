@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Quản lý đơn hàng</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.order.trashed') }}" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Thùng rác
                </a>
            </div>
        </div>

        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('admin.order.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo mã đơn hoặc tên khách hàng"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        @if (request()->has('search'))
            <div class="mb-4">
                <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Quay lại danh sách đầy đủ
                </a>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (!isset($orders))
            <div class="alert alert-danger">Biến $orders không được truyền từ controller.</div>
        @else
            <!-- Bảng đơn hàng dạng accordion -->
            <div class="accordion" id="ordersAccordion">
                @forelse ($orders as $order)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $order['id'] }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order['id'] }}" aria-expanded="false"
                                aria-controls="collapse{{ $order['id'] }}">
                                <div class="row w-100 align-items-center">
                                    <div class="col-2">Mã đơn: {{ $order['id'] }}</div>
                                    <div class="col-2">Khách hàng: {{ $order['user_name'] }}</div>
                                    <div class="col-2">Số lượng: {{ $order['total_quantity'] }}</div>
                                    <div class="col-2">Phí vận chuyển: {{ number_format($order['shipping_fee'] ?? 0, 2) }} VND</div>
                                    <div class="col-2">Giảm giá coupon: {{ number_format($order['coupon_discount'] ?? 0, 2) }} VND</div>
                                    <div class="col-2">Tổng tiền: {{ number_format($order['final_total'] ?? 0, 2) }} VND</div>
                                    <div class="col-2">Trạng thái: {{ $order['status_vietnamese'] }}</div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $order['id'] }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $order['id'] }}" data-bs-parent="#ordersAccordion">
                            <div class="accordion-body">
                                <!-- Chi tiết đơn hàng -->
                                <div class="mb-3">
                                    <strong>Phương thức thanh toán:</strong> {{ $order['payment_method_vietnamese'] }}<br>
                                    <strong>Địa chỉ giao hàng:</strong> {{ $order['recipient_address'] }}<br>
                                    <strong>Tên người nhận:</strong> {{ $order['recipient_name'] }}<br>
                                    <strong>Số điện thoại:</strong> {{ $order['recipient_phone'] }}<br>
                                    <strong>Phí vận chuyển:</strong> {{ number_format($order['shipping_fee'] ?? 0, 2) }} VND<br>
                                    <strong>Giảm giá coupon:</strong> {{ number_format($order['coupon_discount'] ?? 0, 2) }} VND<br>
                                    <strong>Phương thức vận chuyển:</strong> {{ $order['shipping_method_name'] }}<br>
                                    <strong>Mã giảm giá:</strong> {{ $order['coupon_code'] }}<br>
                                    <strong>Ngày tạo:</strong> {{ $order['created_at'] }}<br>
                                    <strong>Ngày giao hàng:</strong>
                                    {{ $order['shipped_at'] ? $order['shipped_at']->format('d/m/Y') : 'Chưa xác định' }}
                                </div>

                                <!-- Bảng chi tiết sản phẩm -->
                                <h5>Chi tiết đơn hàng</h5>
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
                                        @foreach ($order['order_items'] as $item)
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
                                <div class="mt-3">
                                    <p><strong>Tổng tiền sản phẩm (tính toán):</strong> {{ number_format($itemTotalSum, 2) }} VND</p>
                                    <p><strong>Tổng tiền sản phẩm (lưu trữ):</strong> {{ number_format($order['subtotal'], 2) }} VND</p>
                                    <p><strong>Phí vận chuyển:</strong> {{ number_format($order['shipping_fee'] ?? 0, 2) }} VND</p>
                                    <p><strong>Giảm giá coupon:</strong> {{ number_format($order['coupon_discount'] ?? 0, 2) }} VND</p>
                                    <p><strong>Tổng tiền cuối cùng:</strong> {{ number_format($order['final_total'] ?? 0, 2) }} VND</p>

                                    @if (abs($itemTotalSum - $order['subtotal']) > 0.01)
                                        <div class="alert alert-warning">
                                            Cảnh báo: Tổng tiền sản phẩm tính toán ({{ number_format($itemTotalSum, 2) }} VND) không khớp với tổng tiền lưu trữ ({{ number_format($order['subtotal'], 2) }} VND).
                                        </div>
                                    @endif
                                    @if (abs($order['final_total'] - ($order['subtotal'] + $order['shipping_fee'] - $order['coupon_discount'])) > 0.01)
                                        <div class="alert alert-warning">
                                            Cảnh báo: Tổng tiền cuối cùng ({{ number_format($order['final_total'], 2) }} VND) không khớp với công thức ({{ number_format($order['subtotal'] + $order['shipping_fee'] - $order['coupon_discount'], 2) }} VND).
                                        </div>
                                    @endif
                                </div>

                                <!-- Hành động -->
                                <div class="mt-3">
                                    @if ($order['status'] === 'pending')
                                        <form action="{{ route('admin.order.updateOrders', $order['id']) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn hàng này?');" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="btn btn-success btn-sm">Xác nhận</button>
                                        </form>
                                    @elseif ($order['status'] === 'processing')
                                        <form action="{{ route('admin.order.updateOrders', $order['id']) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xác nhận giao hàng này?');" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="btn btn-success btn-sm">Xác nhận giao hàng</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.order.show', $order['id']) }}" class="btn btn-info btn-sm">Xem</a>
                                    <a href="{{ route('admin.order.edit', $order['id']) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    @if (in_array($order['status'], ['cancelled', 'returned', 'delivered']))
                                        <form action="{{ route('admin.order.destroy', $order['id']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn chuyển đơn hàng này vào thùng rác?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Không có đơn hàng nào.</div>
                @endforelse
            </div>

            <!-- Phân trang -->
            {{ $pagination->links('pagination::bootstrap-5') }}
        @endif
    </div>
@endsection