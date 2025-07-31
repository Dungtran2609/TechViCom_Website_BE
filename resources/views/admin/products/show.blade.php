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
                    <table class="table table-striped align-middle">
                        <tr>
                            <th>ID sản phẩm:</th>
                            <td>{{ $product->id }}</td>
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
                            <th>Mã SKU:</th>
                            <td>{{ $product->sku ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Loại sản phẩm:</th>
                            <td>
                                @if ($product->type === 'simple')
                                    Sản phẩm đơn thể
                                @elseif ($product->type === 'variable')
                                    Sản phẩm có biến thể
                                @else
                                    Không xác định
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td>{{ $product->display_price }} đ</td>
                        </tr>
                        <tr>
                            <th>Giá khuyến mãi:</th>
                            <td>{{ number_format($product->sale_price) }} đ</td>
                        </tr>
                        <tr>
                            <th>Tồn kho:</th>
                            <td>{{ $product->stock }}</td>
                        </tr>
                        <tr>
                            <th>Cảnh báo khi tồn kho dưới:</th>
                            <td>{{ $product->low_stock_amount }}</td>
                        </tr>
                        <tr>
                            <th>Danh mục:</th>
                            <td>{{ $product->category->name ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Thương hiệu:</th>
                            <td>{{ $product->brand->name ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $product->status === 'active' ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                        </tr>
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
                            <p><strong>Mô tả ngắn:</strong> {{ $product->short_description }}</p>
                        @endif
                        @if ($product->long_description)
                            <p><strong>Mô tả chi tiết:</strong><br>{!! nl2br(e($product->long_description)) !!}</p>
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
                             class="img-fluid rounded shadow-sm mb-2">
                    @else
                        <p class="text-muted">Chưa có ảnh.</p>
                    @endif
                </div>
            </div>

            {{-- Ảnh phụ --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Ảnh phụ</h5>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    @forelse ($product->allImages as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                             class="img-thumbnail rounded" width="100">
                    @empty
                        <p class="text-muted">Không có ảnh phụ.</p>
                    @endforelse
                </div>
            </div>

            {{-- Thao tác nhanh --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')">
                                <i class="fas fa-trash me-1"></i> Xoá sản phẩm
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
