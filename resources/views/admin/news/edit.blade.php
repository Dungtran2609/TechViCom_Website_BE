@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">✏️ Chỉnh sửa bài viết: {{ $news->title }}</h2>

        {{-- Hiển thị lỗi nếu có --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu.<br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form cập nhật --}}
        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-4 rounded shadow-sm border">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Tiêu đề --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">🏷️ Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}"
                        required>
                </div>

                {{-- Danh mục --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">📂 Danh mục <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->category_id }}"
                                {{ old('category_id', $news->category_id) == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tác giả --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">✍️ Tác giả</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                <input type="hidden" name="author_id" value="{{ auth()->id() }}">
            </div>
            
            {{-- Ảnh hiện tại --}}
            <div class="mb-3">
                <label class="form-label">🖼️ Ảnh hiện tại</label><br>
                @if ($news->image)
                    <img src="{{ asset($news->image) }}" width="200" class="img-thumbnail" alt="Ảnh bài viết">
                @else
                    <p class="text-muted fst-italic">Chưa có ảnh</p>
                @endif
            </div>

            {{-- Ảnh mới --}}
            <div class="mb-3">
                <label class="form-label">📷 Ảnh mới (nếu muốn thay)</label>
                <input type="file" name="image" class="form-control">
            </div>

            {{-- Nội dung --}}
            <div class="mb-3">
                <label class="form-label">📝 Nội dung <span class="text-danger">*</span></label>
                <textarea name="content" id="editor" class="form-control" rows="10" required>{{ old('content', $news->content) }}</textarea>
            </div>

            {{-- Trạng thái & Ngày đăng --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">📢 Trạng thái</label>
                    <select name="status" class="form-select" required>
                        <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Đã
                            đăng</option>
                        <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Nháp
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">📅 Ngày đăng</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            {{-- Nút hành động --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">← Quay lại</a>
                <button type="submit" class="btn btn-success">💾 Cập nhật</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    {{-- CKEditor 4 Full --}}
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
