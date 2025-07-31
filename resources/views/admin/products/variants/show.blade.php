@extends('admin.layouts.app')

@section('title', 'Chi tiết biến thể')

@section('content')
<div class="container">
    <h2>Chi tiết biến thể của: {{ $product->name }}</h2>

    <div class="mb-3">
        <label>SKU:</label>
        <p>{{ $variant->sku }}</p>
    </div>

    <div class="mb-3">
        <label>Giá:</label>
        <p>{{ number_format($variant->price) }} đ</p>
    </div>

    <div class="mb-3">
        <label>Giá khuyến mãi:</label>
        <p>{{ number_format($variant->sale_price) }} đ</p>
    </div>

    <div class="mb-3">
        <label>Tồn kho:</label>
        <p>{{ $variant->stock }}</p>
    </div>

    <div class="mb-3">
        <label>Trọng lượng:</label>
        <p>{{ $variant->weight ?? 'Chưa có' }} kg</p>
    </div>

    <div class="mb-3">
        <label>Kích thước:</label>
        <p>{{ $variant->dimensions ?? 'Chưa có' }}</p>
    </div>

    <div class="mb-3">
        <label>Ảnh:</label><br>
        @if ($variant->image)
            <img src="{{ asset('storage/' . $variant->image) }}" width="100">
        @else
            <p>Không có ảnh</p>
        @endif
    </div>

    <div class="mb-3">
        <label>Thuộc tính:</label><br>
        @foreach ($attributes as $attr)
            <span class="badge bg-secondary">{{ $attr->attribute->name }}: {{ $attr->value }}</span>
        @endforeach
    </div>

    <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
