@extends('admin.layouts.app')

@section('title', 'Chi tiết biến thể sản phẩm')

@section('content')
<div class="container py-4">
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header">
            <h4 class="mb-0">Chi tiết biến thể của: <span class="text-primary">{{ $product->name }}</span></h4>
        </div>
        <div class="card-body row">
            <div class="col-md-4 text-center">
                @if ($variant->image)
                    <img src="{{ asset('storage/' . $variant->image) }}" class="img-fluid rounded mb-3" style="max-height: 220px;">
                @elseif ($product->type === 'simple' && $product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" class="img-fluid rounded mb-3" style="max-height: 220px;">
                @else
                    <span class="text-muted fst-italic">Không có ảnh</span>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 180px;">ID biến thể:</th>
                            <td>#{{ $variant->id }}</td>
                        </tr>
                        <tr>
                            <th>SKU:</th>
                            <td>{{ $variant->sku ?? 'Chưa có' }}</td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td>
                                @if ($product->type === 'simple')
                                    {{ number_format($product->price) }} đ
                                @else
                                    {{ number_format($variant->price) }} đ
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Giá khuyến mãi:</th>
                            <td>
                                @if ($product->type === 'simple')
                                    {{ number_format($product->sale_price) }} đ
                                @else
                                    {{ number_format($variant->sale_price) }} đ
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tồn kho:</th>
                            <td>
                                @if ($product->type === 'simple')
                                    {{ $product->stock }}
                                @else
                                    {{ $variant->stock }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Khối lượng:</th>
                            <td>{{ $variant->weight ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Kích thước:</th>
                            <td>{{ $variant->dimensions ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Thuộc tính:</th>
                            <td>
                                @foreach ($attributes as $attrVal)
                                    <span class="badge bg-secondary mb-1">
                                        {{ $attrVal->attribute->name }}: {{ $attrVal->value }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td class="text-muted">{{ $variant->created_at->format('H:i, d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Lần cập nhật cuối:</th>
                            <td class="text-muted">{{ $variant->updated_at->format('H:i, d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <a href="{{ route('admin.products.variants.edit', $variant->id) }}" class="btn btn-warning me-2">Sửa</a>
                    <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại danh sách biến thể</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
