@extends('admin.layouts.app')

@section('content')
<h1>Chi tiết bài viết</h1>

<div class="mb-3 border-bottom pb-3 bg-light-subtle rounded p-3 mb-4">
    <strong>Tiêu đề:</strong> {{ $news->title }}
</div>

<div class="mb-3 border-bottom pb-3 bg-light-subtle rounded p-3 mb-4">
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

<div class="mb-3 border-bottom pb-3 bg-light-subtle rounded p-3 mb-4">
    <strong>Nội dung:</strong>
    <div class="border p-2">
        {!! $news->content !!}
    </div>
</div>

{{-- Gộp chung --}}
<div class="mb-3 border-bottom pb-3 bg-light-subtle rounded p-3 mb-4">
    <strong>Trạng thái:</strong>
    <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'secondary' }}">
        {{ $news->status === 'published' ? 'Đã đăng' : 'Nháp' }}
    </span>
</div>

<div class="mb-3 border-bottom pb-3 bg-light-subtle rounded p-3 mb-4">
    <strong>Ngày đăng:</strong>
    {{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}
</div>

<a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Quay lại</a>

{{-- Đóng khung bình luận --}}
<div class="mt-5 mb-5 border-top pt-3 bg-light-subtle rounded p-3">
    <h3>Bình luận</h3>
    @if ($news->comments->isEmpty())
        <p>Chưa có bình luận nào.</p>
    @else
        <ul class="list-group">
            @foreach ($news->comments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->name ?? 'Người dùng không xác định' }}:</strong>
                    <p>{{ $comment->content }}</p>
                    <small class="text-muted">
                        {{ $comment->created_at->format('d/m/Y H:i') }}
                        @if ($comment->updated_at)
                            (Cập nhật: {{ $comment->updated_at->format('d/m/Y H:i') }})
                        @endif
                    </small>
                </li>
            @endforeach
        </ul>
    @endif
</div>                       
@endsection
