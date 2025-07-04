@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        @auth
            <span class="me-2">Xin chào, {{ Auth::user()->name }}</span>
            <a href="{{ route('account.show') }}" class="btn btn-outline-info me-2">Quản trị tài khoản</a>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Đăng nhập</a>
            <a href="{{ route('register') }}" class="btn btn-success">Đăng ký</a>
        @endauth
    </div>

    <h2>Danh mục sản phẩm</h2>
    <div class="row mb-4">
        @foreach($categories as $category)
            <div class="col-md-3 mb-2">
                <div class="card">
                    <div class="card-body text-center">
                        <strong>{{ $category->name }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h2>Sản phẩm mới nhất</h2>
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection