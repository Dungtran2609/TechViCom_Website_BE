@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary">📝 Sửa bài viết</h2>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Danh mục bài viết <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}"
                            {{ old('category_id', $news->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh đại diện hiện tại</label><br>
            @if ($news->image)
                <img src="{{ asset($news->image) }}" alt="Ảnh hiện tại" class="img-thumbnail" width="200">
            @else
                <p class="text-muted fst-italic">Chưa có ảnh</p>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Chọn ảnh mới (nếu muốn thay)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
            <textarea name="content" id="editor" class="form-control" rows="18" required>{{ old('content', $news->content) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Trạng thái</label>
                <select name="status" class="form-select" required>
                    <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Đã đăng</option>
                    <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Nháp</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Ngày đăng</label>
                <input type="datetime-local" name="published_at" class="form-control"
                    value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}">
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                ← Quay lại
            </a>
            <button type="submit" class="btn btn-success">
                💾 Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
{{-- CKEditor 4 + Laravel File Manager --}}
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        height: 400,
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{ csrf_token() }}',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{ csrf_token() }}'
    });
</script>
@endsection
