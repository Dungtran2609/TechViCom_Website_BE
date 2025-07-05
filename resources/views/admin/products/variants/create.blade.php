@extends('admin.layouts.app')


@section('title', 'Thêm biến thể sản phẩm')

@section('content')
<div class="container">
    <h2>Thêm biến thể cho: {{ $product->name }}</h2>

    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Số lượng tồn kho</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
<div class="mb-3">
    <label>Khối lượng (gram)</label>
    <input type="number" name="weight" class="form-control" step="0.01" required>
</div>
<div class="mb-3">
    <label>Kích thước (VD: 10x20x5 cm)</label>
    <input type="text" name="dimensions" class="form-control" required>
</div>

        <div class="mb-3">
            <label>Ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>
        
        <div class="mb-3">
            <label>Chọn thuộc tính</label><br>
            @foreach ($attributes as $attribute)
                <label>{{ $attribute->name }}:</label>
                <select name="attribute_values[]" class="form-select mb-2" required>
                    <option value="">-- Chọn --</option>
                    @foreach ($attribute->values as $value)
                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                    @endforeach
                </select>
            @endforeach
        </div>

        <button class="btn btn-success">Thêm mới</button>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
