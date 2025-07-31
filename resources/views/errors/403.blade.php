
@extends('admin.layouts.app') {{-- layout của bạn có header/footer --}}

@section('title', '403 - Không có quyền truy cập')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="display-4 text-danger"></h1>
        <p class="lead">Bạn không có quyền truy cập vào trang này.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection

