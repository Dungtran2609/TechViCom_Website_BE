@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Chi tiết liên hệ</h1>

    <div class="card">
        <div class="card-body">
            <h5><strong>Họ tên:</strong> {{ $contact->name }}</h5>

            <p><strong>Email:</strong> {{ $contact->email ?? 'Không có' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $contact->phone ?? 'Không có' }}</p>
            <p><strong>Nội dung:</strong></p>
            <p class="border p-3 rounded bg-light">{{ $contact->message }}</p>

            <p><strong>Gửi lúc:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.lienhe.index') }}" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
</div>
@endsection
