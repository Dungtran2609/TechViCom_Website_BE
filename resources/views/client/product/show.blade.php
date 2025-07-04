@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">← Quay lại trang chủ</a>

    <div class="row">
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh" class="img-fluid rounded shadow">
            @endif
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>

            <p><strong>Giá gốc:</strong> 
                <del>{{ number_format($product->price, 0, ',', '.') }} VNĐ</del>
            </p>

            <p><strong>Giá khuyến mãi:</strong> 
                <span class="text-danger fw-bold">
                    {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }} VNĐ
                </span>
            </p>

            <p><strong>Số lượng còn:</strong> {{ $product->stock }}</p>

            <p><strong>Hãng sản xuất:</strong> 
                {{ $product->brand->name ?? 'Chưa xác định' }}
            </p>

            <p><strong>Trạng thái:</strong> 
                <span class="{{ $product->status == 'active' ? 'text-success' : 'text-muted' }}">
                    {{ $product->status == 'active' ? 'Đang bán' : 'Ngừng bán' }}
                </span>
            </p>

            <hr>
            <h5>Mô tả sản phẩm</h5>
            <p>{{ $product->description }}</p>

            {{-- Thêm vào giỏ hàng / Mua ngay có thể đặt ở đây --}}
            <a href="#" class="btn btn-primary mt-3">Thêm vào giỏ hàng</a>
        </div>
    </div>
</div>
@endsection
