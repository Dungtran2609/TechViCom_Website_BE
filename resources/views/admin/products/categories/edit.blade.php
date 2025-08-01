@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa danh mục</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Tên danh mục --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Danh mục cấp trên --}}
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Danh mục cha</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror"
                                    id="parent_id" name="parent_id">
                                <option value="">Không có</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (old('parent_id', $category->parent_id) == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh hiện tại --}}
                        @if($category->image)
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại</label><br>
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh danh mục" 
                                     style="max-height: 120px;" class="img-thumbnail">
                            </div>
                        @endif

                        {{-- Thay đổi ảnh --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Thay ảnh mới</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút điều hướng --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
