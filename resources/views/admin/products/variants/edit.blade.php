@extends('admin.layouts.app')


@section('title', 'Sửa biến thể sản phẩm')

@section('content')
<div class="container">
    <h2>Sửa biến thể của: {{ $product->name }}</h2>

    <form action="{{ route('admin.products.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
        </div>

        <div class="mb-3">
            <label>Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control" value="{{ $variant->sale_price }}">
        </div>

        <div class="mb-3">
            <label>Số lượng tồn kho</label>
            <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
        </div>

        <div class="mb-3">
            <label>Ảnh hiện tại:</label><br>
            @if ($variant->image)
                <img src="{{ asset('storage/' . $variant->image) }}" width="80"><br>
            @else
                <span>Không có ảnh</span>
            @endif
        </div>

        <div class="mb-3">
            <label>Ảnh mới (nếu muốn thay):</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label>Thuộc tính</label><br>
            @foreach ($attributes as $attribute)
                <label>{{ $attribute->name }}:</label>
                
                <select name="attribute_values[]" class="form-select mb-2" required>
                    <option value="">-- Chọn --</option>
                    @foreach ($attribute->values as $value)
                        <option value="{{ $value->id }}" {{ in_array($value->id, $selectedValues) ? 'selected' : '' }}>
    {{ $value->value }} {{-- thay vì $value->name --}}
</option>
                    @endforeach
                </select>
            @endforeach
        </div>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
