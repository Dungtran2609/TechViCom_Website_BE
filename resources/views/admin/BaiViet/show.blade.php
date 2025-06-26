@extends('admin.layouts.app')

@section('content')
<h1>Chi tiết bài viết</h1>

<div class="mb-3">
    <strong>Tiêu đề:</strong> {{ $news->title }}
</div>

<div class="mb-3">
    <strong>Danh mục:</strong>
    <span class="badge bg-info-subtle text-dark">
        {{ $news->category?->name ?? 'Không có' }}
    </span>
</div>

<div class="mb-3">
    <strong>Ảnh đại diện:</strong><br>
    @if ($news->image)
        <img src="{{ asset($news->image) }}" alt="Ảnh bài viết" width="300">
    @else
        <p>Chưa có ảnh</p>
    @endif
</div>

<div class="mb-3">
    <strong>Nội dung:</strong>
    <div class="border p-2">
        {!! $news->content !!}
    </div>
</div>

<div class="mb-3">
    <strong>Trạng thái:</strong>
    <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'secondary' }}">
        {{ $news->status === 'published' ? 'Đã đăng' : 'Nháp' }}
    </span>
</div>

<div class="mb-3">
    <strong>Ngày đăng:</strong>
    {{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}
</div>

<a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
