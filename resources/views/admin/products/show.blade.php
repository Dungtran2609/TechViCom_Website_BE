@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết sản phẩm</h1>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Thông tin chi tiết --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin sản phẩm</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID sản phẩm:</th>
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
                            <td>{{ number_format($product->price) }} đ</td>
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mô tả</h5>
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
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ảnh đại diện</h5>
                </div>
                <div class="card-body text-center">
                    @if ($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 200px;">
                    @else
                        <p class="text-muted">Chưa có ảnh.</p>
                    @endif
                </div>
            </div>

            {{-- Ảnh phụ --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ảnh phụ</h5>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    @forelse ($product->allImages as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="img-thumbnail" style="width: 100px;">
                    @empty
                        <p class="text-muted">Không có ảnh phụ.</p>
                    @endforelse
                </div>
            </div>

            {{-- Thao tác nhanh --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')">
                                <i class="fas fa-trash"></i> Xoá sản phẩm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
