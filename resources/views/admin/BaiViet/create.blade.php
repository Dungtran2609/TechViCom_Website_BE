@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-primary"><i class="bi bi-plus-square me-2"></i>Thêm bài viết</h2>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-4 rounded shadow-sm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Danh mục bài viết <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}"
                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tác giả <span class="text-danger">*</span></label>
                <select name="author_id" class="form-select" required>
                    <option value="">-- Chọn tác giả --</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                <textarea name="content" id="editor" class="form-control" rows="15" required>{{ old('content') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select" required>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Đã đăng</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Nháp</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Ngày đăng</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at') }}">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    ← Quay lại
                </a>
                <button type="submit" class="btn btn-success">
                    💾 Lưu bài viết
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
