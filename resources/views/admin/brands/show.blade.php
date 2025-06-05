@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Brand Details</h1>
        <div>
            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Brand
            </a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Thông tin cơ bản --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Brand ID:</th>
                            <td>{{ $brand->brand_id }}</td>
                        </tr>
                        <tr>
                            <th>Brand Name:</th>
                            <td>{{ $brand->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug:</th>
                            <td>{{ $brand->slug }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                    {{ $brand->status ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Ảnh và thao tác nhanh --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}" 
                             alt="{{ $brand->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 200px;">
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Brand
                        </a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Are you sure you want to delete this brand?')">
                                <i class="fas fa-trash"></i> Delete Brand
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mô tả thương hiệu --}}
    @if($brand->description)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $brand->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
