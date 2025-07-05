@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chỉnh sửa thuộc tính</h1>
    <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.attributes.update', $attribute) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên thuộc tính</label>
                <input type="text" name="name" value="{{ old('name', $attribute->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Loại</label>
                <select name="type" class="form-select" required>
                    <option value="select" @selected($attribute->type === 'select')>Select</option>
                    <option value="text" @selected($attribute->type === 'text')>Text</option>
                    <option value="color" @selected($attribute->type === 'color')>Color</option>
                    <option value="number" @selected($attribute->type === 'number')>Number</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $attribute->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
@endsection
