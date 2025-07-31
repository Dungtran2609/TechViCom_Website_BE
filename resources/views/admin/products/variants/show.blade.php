@extends('admin.layouts.app')

@section('title', 'Chi tiết biến thể')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 h4">Chi tiết biến thể của: <strong>{{ $product->name }}</strong></h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>SKU:</strong> {{ $variant->sku }}</p>
            <p><strong>Giá:</strong> {{ number_format($variant->price) }} đ</p>
            <p><strong>Giá khuyến mãi:</strong> {{ number_format($variant->sale_price) }} đ</p>
            <p><strong>Tồn kho:</strong> {{ $variant->stock }}</p>
            <p><strong>Trọng lượng:</strong> {{ $variant->weight ?? 'Chưa có' }} kg</p>
            <p><strong>Kích thước:</strong> {{ $variant->dimensions ?? 'Chưa có' }}</p>

            <p><strong>Ảnh:</strong><br>
                @if ($variant->image)
                    <img src="{{ asset('storage/' . $variant->image) }}" class="img-thumbnail mt-2" style="width: 100px; height: 100px;">
                @else
                    <span class="text-muted fst-italic">Không có ảnh</span>
                @endif
            </p>

            <p><strong>Thuộc tính:</strong><br>
                @foreach ($attributes as $attr)
                    <span class="badge bg-secondary me-1 mb-1">
                        {{ $attr->attribute->name }}: {{ $attr->value }}
                    </span>
                @endforeach
            </p>

            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary mt-3">← Quay lại</a>
        </div>
    </div>
</div>
@endsection
