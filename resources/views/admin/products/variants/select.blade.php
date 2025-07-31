@extends('admin.layouts.app')

@section('title', 'Chọn sản phẩm để quản lý biến thể')

@section('content')
<div class="container">
    <h2>Chọn sản phẩm để quản lý biến thể</h2>

    <ul class="list-group">
        @foreach ($products as $product)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $product->name }}
                <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-sm btn-primary">Quản lý biến thể</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
