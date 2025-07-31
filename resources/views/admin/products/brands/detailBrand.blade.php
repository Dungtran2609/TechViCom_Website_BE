@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết thương hiệu</h1>
        <div>
            <a href="{{ route('admin.products.brands.edit', $brand) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Sửa thương hiệu
            </a>
            <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về trang danh sách
            </a>
        </div>
    </div>

    <div class="row">
        {{-- LEFT COLUMN: Basic Info --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">ID:</th>
                            <td>{{ $brand->brand_id }}</td>
                        </tr>
                        <tr>
                            <th>Tên thương hiệu:</th>
                            <td>{{ $brand->name }}</td>
                        </tr>
                        <tr>
                            <th>Chuỗi ký tự:</th>
                            <td>{{ $brand->slug }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả:</th>
                            <td>{{ $brand->description ?? 'Không có mô tả' }}</td>
                        </tr>
                        <tr>
                            <th>Hình ảnh:</th>
                            <td>
                                @if($brand->image)
                                    <a href="{{ asset('storage/' . $brand->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $brand->image) }}" width="120" alt="Hình ảnh thương hiệu" class="img-thumbnail">
                                    </a>
                                    <small class="text-muted">(Nhấn để xem kích thước gốc)</small>
                                @else
                                    <span class="text-muted">Không có hình ảnh</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                    {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                                <br>
                                <small class="text-muted">Cập nhật lần cuối: {{ $brand->updated_at->format('d/m/Y H:i') }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: (Không áp dụng cho brands, giữ trống) --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin thêm</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">Không có thông tin phụ thêm cho thương hiệu.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection