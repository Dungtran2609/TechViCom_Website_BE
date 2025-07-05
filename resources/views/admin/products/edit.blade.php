@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Chỉnh sửa sản phẩm</h4>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
        </div>

        {{-- SKU --}}
        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
        </div>

        {{-- Loại sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Loại sản phẩm</label>
            <select name="type" class="form-select" disabled>
                <option value="simple" {{ $product->type === 'simple' ? 'selected' : '' }}>Sản phẩm đơn</option>
                <option value="variable" {{ $product->type === 'variable' ? 'selected' : '' }}>Sản phẩm biến thể</option>
            </select>
            <div class="form-text text-muted">Không thể thay đổi loại sau khi tạo</div>
        </div>

        {{-- Giá --}}
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
        </div>

        {{-- Giá khuyến mãi --}}
        <div class="mb-3">
            <label class="form-label">Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
        </div>

        {{-- Tồn kho --}}
        <div class="mb-3">
            <label class="form-label">Tồn kho</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
        </div>

        {{-- Cảnh báo tồn kho --}}
        <div class="mb-3">
            <label class="form-label">Cảnh báo tồn kho dưới</label>
            <input type="number" name="low_stock_amount" class="form-control" value="{{ old('low_stock_amount', $product->low_stock_amount) }}">
        </div>

        {{-- Danh mục --}}
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Thương hiệu --}}
        <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="brand_id" class="form-select">
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Trạng thái --}}
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Hiển thị</option>
                <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        {{-- Ảnh đại diện --}}
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label><br>
            @if ($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" style="max-height: 120px;" class="img-thumbnail mb-2">
            @endif
            <input type="file" name="thumbnail" class="form-control">
        </div>

{{-- Ảnh phụ --}}
<div class="mb-3">
    <label class="form-label fw-bold">Ảnh phụ khác</label>
    <div id="existingImages">
        @foreach ($product->allImages as $index => $image)
            <div class="mb-2 d-flex align-items-center gap-2">
                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" style="height: 80px;">
                
                {{-- Ảnh thay thế --}}
                <input type="file" name="existing_images[{{ $image->id }}]" class="form-control" accept="image/*">

                {{-- Nút xoá ảnh --}}
                <div class="form-check">
                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="form-check-input">
                    <label class="form-check-label">Xoá</label>
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    <label class="form-label">Thêm ảnh mới</label>
    <div id="galleryWrapper"></div>
    <button type="button" class="btn btn-sm btn-primary mt-2" id="btnAddImage">Thêm ảnh</button>
</div>

        {{-- Mô tả ngắn --}}
        <div class="mb-3">
            <label class="form-label">Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        {{-- Mô tả chi tiết --}}
        <div class="mb-3">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description', $product->long_description) }}</textarea>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">Quay lại</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('btnAddImage').addEventListener('click', function () {
        const wrapper = document.getElementById('galleryWrapper');
        const div = document.createElement('div');
        div.classList.add('d-flex', 'align-items-center', 'mb-2', 'gallery-item');

        div.innerHTML = `
            <input type="file" name="gallery[]" class="form-control me-2" accept="image/*">
            <button type="button" class="btn btn-danger btn-sm btnRemoveImage">Xoá</button>
        `;

        wrapper.appendChild(div);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btnRemoveImage')) {
            e.preventDefault();
            e.target.closest('.gallery-item').remove();
        }
    });
</script>
@endpush
