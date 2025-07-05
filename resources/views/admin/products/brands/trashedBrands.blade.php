@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Thùng rác thương hiệu</h1>
            <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về danh sách thương hiệu
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                @if($brands->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên thương hiệu</th>
                                    <th>Mô tả</th>
                                    <th>Hình ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày xoá</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->description ?? 'Không có mô tả' }}</td>
                                        <td>
                                            @if($brand->image)
                                                <img src="{{ asset('storage/' . $brand->image) }}" width="50"
                                                    alt="Hình ảnh thương hiệu">
                                            @else
                                                <span class="text-muted">Không có hình ảnh</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                                {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                            </span>
                                        </td>
                                        <td>{{ $brand->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('admin.products.brands.restore', $brand->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục thương hiệu này không?')">
                                                    <i class="fas fa-undo"></i> Khôi phục
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.products.brands.forceDelete', $brand->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn thương hiệu này không?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Xoá vĩnh viễn
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $brands->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @else
                    <p class="text-muted">Không có thương hiệu nào trong thùng rác.</p>
                @endif
            </div>
        </div>
    </div>
@endsection