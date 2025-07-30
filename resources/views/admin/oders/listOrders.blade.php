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
                                    @if (!empty($order['coupon_code']) && ($order['coupon_discount'] ?? 0) > 0)
                                        <div class="col-2">Giảm giá coupon: {{ number_format($order['coupon_discount'], 2) }} VND</div>
                                    @endif
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
                                    <strong>Địa chỉ giao hàng:</strong>
                                    <div class="col-2">Tỉnh: {{ array_key_exists('province_name', $order) ? $order['province_name'] : (array_key_exists('province_id', $order) ? $order['province_id'] : 'Chưa có') }}</div>
                                    <div class="col-2">Quận: {{ array_key_exists('district_name', $order) ? $order['district_name'] : (array_key_exists('district_id', $order) ? $order['district_id'] : 'Chưa có') }}</div>
                                    <div class="col-2">Phường: {{ array_key_exists('ward_name', $order) ? $order['ward_name'] : (array_key_exists('ward_id', $order) ? $order['ward_id'] : 'Chưa có') }}</div><br>
                                    <strong>Tên người nhận:</strong> {{ $order['recipient_name'] }}<br>
                                    <strong>Số điện thoại:</strong> {{ $order['recipient_phone'] }}<br>
                                    <strong>Phí vận chuyển:</strong> {{ number_format($order['shipping_fee'] ?? 0, 2) }} VND<br>
                                    @if (!empty($order['coupon_code']) && ($order['coupon_discount'] ?? 0) > 0)
                                        <strong>Giảm giá coupon:</strong> {{ number_format($order['coupon_discount'], 2) }} VND<br>
                                        <strong>Mã giảm giá:</strong> {{ $order['coupon_code'] }}<br>
                                    @endif
                                    <strong>Phương thức vận chuyển:</strong> {{ $order['shipping_method_name'] }}<br>
                                    <strong>Ngày tạo:</strong> {{ $order['created_at'] }}<br>
                                    <strong>Ngày giao hàng:</strong>
                                    {{ $order['shipped_at'] ? $order['shipped_at']->format('d/m/Y') : 'Chưa xác định' }}
                                </div>

                                <!-- Bảng chi tiết sản phẩm -->
                                <!-- (không thay đổi - giữ nguyên) -->

                                <div class="mt-3">
                                    <!-- (giữ nguyên các dòng khác) -->
                                    @if (!empty($order['coupon_code']) && ($order['coupon_discount'] ?? 0) > 0)
                                        <p><strong>Giảm giá coupon:</strong> {{ number_format($order['coupon_discount'], 2) }} VND</p>
                                    @endif
                                    <p><strong>Tổng tiền cuối cùng:</strong> {{ number_format($order['final_total'] ?? 0, 2) }} VND</p>

                                    <!-- Các cảnh báo - không đổi -->
                                </div>

                                <!-- Hành động - giữ nguyên -->
                                                <!-- Hành động -->
                                                <div class="mt-3">
                                                    @if ($order['status'] === 'pending')
                                                        <form action="{{ route('admin.order.updateOrders', $order['id']) }}" method="POST"
                                                            onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn hàng này?');" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="processing">
                                                            <button type="submit" class="btn btn-success btn-sm">Xác nhận</button>
                                                        </form>
                                                    @elseif ($order['status'] === 'processing')
                                                        <form action="{{ route('admin.order.updateOrders', $order['id']) }}" method="POST"
                                                            onsubmit="return confirm('Bạn có chắc muốn xác nhận giao hàng này?');" style="display:inline;">
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
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn chuyển đơn hàng này vào thùng rác?')">
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

@push('scripts')
<!-- script cũ giữ nguyên -->
@endpush
