@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm sản phẩm mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tên sản phẩm --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SKU --}}
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                id="sku" name="sku" value="{{ old('sku') }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Ảnh sản phẩm</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                   id="thumbnail" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh phụ --}}
                        <div class="mb-3">
                            <label class="form-label">Ảnh phụ khác</label>
                            <button type="button" class="btn btn-sm btn-primary mb-2" id="btnAddImage">Thêm ảnh</button>
                            <div id="galleryWrapper"></div>
                        </div>

                        {{-- Loại sản phẩm --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" name="type" id="type">
                                <option value="simple" {{ old('type') == 'simple' ? 'selected' : '' }}>Sản phẩm đơn</option>
                                <option value="variable" {{ old('type') == 'variable' ? 'selected' : '' }}>Sản phẩm biến thể</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                                                {{-- Giá gốc --}}
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Giá khuyến mãi --}}                       {{-- Giá gốc --}}
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi<span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                   id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tồn kho --}}
                        <div class="mb-3">
                            <label for="stock" class="form-label">Tồn kho</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" name="stock" value="{{ old('stock') }}">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cảnh báo tồn kho thấp --}}
                        <div class="mb-3">
                            <label for="low_stock_amount" class="form-label">Cảnh báo khi tồn kho dưới mức</label>
                            <input type="number" class="form-control @error('low_stock_amount') is-invalid @enderror"
                                   id="low_stock_amount" name="low_stock_amount" value="{{ old('low_stock_amount') }}">
                            @error('low_stock_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Danh mục --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Thương hiệu</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                      id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả chi tiết --}}
                        <div class="mb-3">
                            <label for="long_description" class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control @error('long_description') is-invalid @enderror"
                                      id="long_description" name="long_description" rows="5">{{ old('long_description') }}</textarea>
                            @error('long_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút hành động --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-warning">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Thêm mới
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnAddImage').addEventListener('click', function (e) {
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

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btnRemoveImage')) {
            e.preventDefault();
            e.target.closest('.gallery-item').remove();
        }
    });
</script>
@endpush
