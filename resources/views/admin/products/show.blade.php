@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary"><i class="fas fa-info-circle me-2"></i>Chi tiết sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        {{-- Thông tin chi tiết --}}
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin sản phẩm</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">ID sản phẩm:</th>
                                <td>#{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên sản phẩm:</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td>{{ $product->slug }}</td>
                            </tr>
                            <tr>
                                <th>Loại sản phẩm:</th>
                                <td>
                                    @if($product->type === 'simple')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fas fa-box me-1"></i>Sản phẩm đơn</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="fas fa-boxes-stacked me-1"></i>Sản phẩm biến thể</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Giá:</th>
                                <td>
                                    @if($product->type === 'simple')
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-danger fw-bold">{{ number_format($product->sale_price, 0, ',', '.') }} đ</span>
                                            <small class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }} đ</small>
                                        @else
                                            <span class="fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                                        @endif
                                    @else
                                        @php
                                            $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                        @endphp
                                        @if($minPrice && $maxPrice && $minPrice != $maxPrice)
                                            <span class="fw-bold">Từ {{ number_format($minPrice, 0, ',', '.') }} đ - {{ number_format($maxPrice, 0, ',', '.') }} đ</span>
                                        @elseif($minPrice)
                                            <span class="fw-bold">{{ number_format($minPrice, 0, ',', '.') }} đ</span>
                                        @else
                                            <span class="text-muted">Chưa có giá</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            @if($product->type === 'simple')
                                <tr>
                                    <th>Mã SKU:</th>
                                    <td>{{ $product->sku ?? 'Chưa có' }}</td>
                                </tr>
                                <tr>
                                    <th>Tồn kho:</th>
                                    <td>{{ $product->stock ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <th>Cảnh báo tồn kho thấp:</th>
                                    <td>{{ $product->low_stock_amount ?? 'Không đặt' }}</td>
                                </tr>
                            @endif
                            
                            <tr>
                                <th>Danh mục:</th>
                                <td>
                                    @if($product->category)
                                        {{ $product->category->name }}
                                        @if($product->category->parent)
                                            <span class="text-muted">, {{ $product->category->parent->name }}</span>
                                        @endif
                                    @else
                                        Không có
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Thương hiệu:</th>
                                <td>{{ $product->brand->name ?? 'Không có' }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge bg-success-subtle text-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Ẩn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Sản phẩm nổi bật:</th>
                                <td>
                                    @if($product->is_featured)
                                        <span class="badge bg-info-subtle text-info">Có</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Không</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td class="text-muted">{{ $product->created_at->format('H:i, d/m/Y') }}</td>
                            </tr>
                             <tr>
                                <th>Lần cập nhật cuối:</th>
                                <td class="text-muted">{{ $product->updated_at->format('H:i, d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mô tả --}}
            @if($product->short_description || $product->long_description)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Mô tả</h5>
                    </div>
                    <div class="card-body">
                        @if ($product->short_description)
                            <h6 class="text-muted">Mô tả ngắn</h6>
                            <p>{{ $product->short_description }}</p>
                        @endif
                        @if ($product->long_description)
                            @if($product->short_description)<hr>@endif
                            <h6 class="text-muted">Mô tả chi tiết</h6>
                            <div>{!! $product->long_description !!}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Ảnh + Thao tác nhanh --}}
        <div class="col-md-4">
            {{-- Ảnh đại diện --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Ảnh đại diện</h5>
                </div>
                <div class="card-body text-center">
                    @if ($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 250px;">
                    @else
                        <p class="text-muted">Chưa có ảnh đại diện.</p>
                    @endif
                </div>
            </div>

            {{-- Ảnh phụ --}}
            @if($product->allImages->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thư viện ảnh</h5>
                    </div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        @foreach ($product->allImages as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" alt="Ảnh phụ">
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Thao tác nhanh --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Chuyển vào thùng rác
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Biến thể sản phẩm --}}
    @if ($product->type === 'variable' && $product->variants && count($product->variants) > 0)
        <div class="card mt-4 shadow-sm border-0">
            <div class="row g-0 align-items-center">
                <div class="col-md-3 text-center p-3">
                    @if ($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             alt="Ảnh sản phẩm"
                             class="img-fluid rounded shadow-sm">
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

        <div class="table-responsive bg-white rounded shadow-sm mt-3 border">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Giá KM</th>
                        <th>Tồn kho</th>
                        <th>Thuộc tính</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->variants as $variant)
                        <tr>
                            <td>
                                @if ($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" class="img-thumbnail" width="60" height="60">
                                @else
                                    <span class="text-muted fst-italic">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ number_format($variant->price) }} đ</td>
                            <td>{{ number_format($variant->sale_price) }} đ</td>
                            <td>{{ $variant->stock }}</td>
                            <td>
                                @foreach ($variant->attributesValue as $attrVal)
                                    <span class="badge bg-info text-dark me-1 mb-1">
                                        {{ $attrVal->attribute->name }}: {{ $attrVal->value }}
                                    </span>
                                @endforeach
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection