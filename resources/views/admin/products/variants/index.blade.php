@extends('admin.layouts.app')

@section('title', 'Danh sách biến thể sản phẩm')

@section('content')
<div class="container py-4">
    <div class="card mb-4 shadow-sm border-0">
        <div class="row g-0 align-items-center">
            <div class="col-md-3 text-center p-3">
                @if ($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                         alt="Ảnh sản phẩm" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 180px;">
                @else
                    <span class="text-muted fst-italic">Không có ảnh</span>
                @endif
            </div>
            <div class="col-md-9 p-3">
                <h4 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>Biến thể của sản phẩm:
                    <strong>{{ $product->name }}</strong>
                </h4>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary mb-3">
        + Thêm biến thể
    </a>

    @if ($variants->isEmpty())
        <div class="alert alert-warning">Chưa có biến thể nào.</div>
    @else
        <div class="table-responsive bg-light rounded shadow-sm">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Giá KM</th>
                        <th>Tồn kho</th>
                        <th>Thuộc tính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variants as $variant)
                        <tr>
                            <td>
                                @if ($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" class="img-thumbnail" style="width: 60px; height: 60px;">
                                @else
                                    <span class="text-muted fst-italic">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ number_format($variant->price) }} đ</td>
                            <td>{{ number_format($variant->sale_price) }} đ</td>
                            <td>{{ $variant->stock }}</td>
                            <td>
                                @foreach ($variant->attributesValue as $attrVal)
                                    <span class="badge bg-secondary mb-1">
                                        {{ $attrVal->attribute->name }}: {{ $attrVal->value }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.products.variants.show', $variant->id) }}" class="btn btn-sm btn-info text-white">Xem</a>
                                <a href="{{ route('admin.products.variants.edit', $variant->id) }}" class="btn btn-sm btn-warning text-white">Sửa</a>
                                <form action="{{ route('admin.products.variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá biến thể này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
