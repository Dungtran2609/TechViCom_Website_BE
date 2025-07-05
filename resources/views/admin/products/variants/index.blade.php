@extends('admin.layouts.app')


@section('title', 'Danh sách biến thể sản phẩm')

@section('content')
    <div class="container">
        <h2>Biến thể của sản phẩm: {{ $product->name }}</h2>

        <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary mb-3">Thêm biến thể</a>

        @if ($variants->isEmpty())
            <div class="alert alert-info">Chưa có biến thể nào.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Giá KM</th>
                        <th>Tồn kho</th>
                        <th>Thuộc tính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variants as $variant)
                        <tr>
                            <td>
                                @if ($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" width="60">
                                @else
                                    Không có ảnh
                                @endif
                            </td>
                            <td>{{ number_format($variant->price) }} đ</td>
                            <td>{{ number_format($variant->sale_price) }} đ</td>
                            <td>{{ $variant->stock }}</td>
                            <td>
                                @foreach ($variant->attributesValue as $attrVal)
                                    <span class="badge bg-secondary">{{ $attrVal->attribute->name }}: {{ $attrVal->value }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.products.variants.show', $variant->id) }}" class="btn btn-sm btn-info">Xem</a>

                                <a href="{{ route('admin.products.variants.edit', $variant->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                                <form action="{{ route('admin.products.variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá biến thể này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
