@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thương hiệu sản phẩm</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.brands.trashed') }}" class="btn btn-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
        <a href="{{ route('admin.products.brands.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm thương hiệu
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.products.brands.index') }}" class="mb-4 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Tìm thương hiệu...">
    <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
    
    @if(request('search'))
        <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Quay lại danh sách đầy đủ
        </a>
    @endif
</form>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu</th>
                        <th>Ảnh</th>
                        <th>Trạng thái</th>
                        <th>Chuỗi ký tự</th>
                        <th width="200px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>
                                <p class="text-dark fw-medium fs-15 mb-0">{{ $brand->name }}</p>
                            </td>
                            <td>
                                @if ($brand->image)
                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="Image" class="avatar-md">
                                    </div>
                                @else
                                    Không có ảnh.
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                    {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.brands.show', $brand) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.products.brands.edit', $brand) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá thương hiệu này?')" title="Xoá">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $brands->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
