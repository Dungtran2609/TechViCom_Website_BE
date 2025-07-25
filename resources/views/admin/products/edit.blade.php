@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Chỉnh sửa sản phẩm: {{ $product->name }}</h4>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $product->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                                    name="short_description" rows="4">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="long_description" class="form-label">Mô tả chi tiết</label>
                                <textarea class="form-control @error('long_description') is-invalid @enderror" id="long_description"
                                    name="long_description" rows="8">{{ old('long_description', $product->long_description) }}</textarea>
                                @error('long_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Media</label>
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thay đổi ảnh đại diện</label>
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                            class="img-thumbnail mb-2" width="150">
                                    @endif
                                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                        id="thumbnail" name="thumbnail" accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Thư viện ảnh</label>
                                    <div class="row g-2 mb-2">
                                        @foreach ($product->allImages as $image)
                                            <div class="col-auto">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        class="img-thumbnail" width="100" alt="Gallery image">
                                                    <div
                                                        class="position-absolute top-0 end-0 p-1 bg-white bg-opacity-75 rounded">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="delete_images[]" value="{{ $image->id }}"
                                                            id="delete_image_{{ $image->id }}">
                                                        <label for="delete_image_{{ $image->id }}"
                                                            class="text-danger small" title="Chọn để xóa">Xóa</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <label class="form-label">Thêm ảnh mới vào thư viện</label>
                                    <div id="galleryWrapper"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnAddImage">Thêm
                                        ảnh</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Loại sản phẩm <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type"
                                    id="type">
                                    <option value="simple" {{ old('type', $product->type) == 'simple' ? 'selected' : '' }}>
                                        Sản phẩm đơn</option>
                                    <option value="variable"
                                        {{ old('type', $product->type) == 'variable' ? 'selected' : '' }}>Sản phẩm biến thể
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="simple-product-fields">
                                <hr>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', $product->price) }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                                    <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                        id="sale_price" name="sale_price"
                                        value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Tồn kho <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                        id="stock" name="stock" value="{{ old('stock', $product->stock) }}">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="low_stock_amount" class="form-label">Cảnh báo khi tồn kho dưới</label>
                                    <input type="number"
                                        class="form-control @error('low_stock_amount') is-invalid @enderror"
                                        id="low_stock_amount" name="low_stock_amount"
                                        value="{{ old('low_stock_amount', $product->low_stock_amount) }}">
                                    @error('low_stock_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                        id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                        placeholder="Để trống để tạo tự động">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id"
                                    id="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Thương hiệu <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id"
                                    id="brand_id">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status"
                                    id="status">
                                    <option value="active"
                                        {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Hiển thị
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_featured"
                                    name="is_featured" value="1"
                                    {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productTypeSelect = document.getElementById('type');
            const simpleProductFieldsContainer = document.querySelector('.simple-product-fields');

            function toggleFields() {
                const isVariable = productTypeSelect.value === 'variable';
                simpleProductFieldsContainer.style.display = isVariable ? 'none' : 'block';
            }
            toggleFields();
            productTypeSelect.addEventListener('change', toggleFields);
        });

        document.getElementById('btnAddImage').addEventListener('click', function(e) {
            e.preventDefault();
            const wrapper = document.getElementById('galleryWrapper');
            const div = document.createElement('div');
            div.classList.add('d-flex', 'align-items-center', 'mb-2', 'gallery-item');
            div.innerHTML = `
            <input type="file" name="gallery[]" class="form-control me-2" accept="image/*">
            <button type="button" class="btn btn-danger btn-sm btnRemoveImage">Xóa</button>
        `;
            wrapper.appendChild(div);
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btnRemoveImage')) {
                e.preventDefault();
                e.target.closest('.gallery-item').remove();
            }
        });
    </script>
@endpush
