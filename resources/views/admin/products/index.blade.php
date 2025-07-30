@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Sản phẩm</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.trashed') }}" class="btn btn-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.products.index') }}" class="mb-4 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Tìm sản phẩm...">
    <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>

    @if(request('search'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Quay lại danh sách đầy đủ
        </a>
    @endif
</form>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Loại</th>
                        <th>Thương hiệu</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                @if ($product->thumbnail)
                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Ảnh sản phẩm" class="avatar-md rounded">
                                    </div>
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
<td>{{ $product->display_price }}</td>

                            <td>
                                <span class="badge {{ $product->type === 'simple' ? 'bg-secondary' : 'bg-warning text-dark' }} text-capitalize">
                                    {{ $product->type === 'simple' ? 'Sản phẩm đơn' : 'Sản phẩm biến thể' }}
                                </span>
                            </td>
                            <td>{{ $product->brand->name ?? 'Không có' }}</td>
                            <td>{{ $product->category->name ?? 'Không có' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $product->status === 'active' ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')" title="Xoá">
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
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
