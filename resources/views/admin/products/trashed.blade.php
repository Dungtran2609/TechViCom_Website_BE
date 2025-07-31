@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh sách sản phẩm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Ảnh</th>
                                <th>Giá</th>
                                <th>Thương hiệu</th>
                                <th>Danh mục</th>
                                <th>Ngày xoá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if ($product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Ảnh sản phẩm" width="60">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                    <td>{{ $product->brand->name ?? 'Không có' }}</td>
                                    <td>{{ $product->category->name ?? 'Không có' }}</td>
                                    <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo"></i> Khôi phục
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá vĩnh viễn sản phẩm này?')">
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
                </div>
            @else
                <p class="text-muted">Không có sản phẩm nào trong thùng rác.</p>
            @endif
        </div>
    </div>
</div>
@endsection
