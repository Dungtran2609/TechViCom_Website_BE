@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Category Details</h1>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        {{-- LEFT COLUMN: Basic Info --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">ID:</th>
                            <td>{{ $category->category_id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug:</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                                    {{ $category->status ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Parent Category:</th>
                            <td>
                                @if($category->parent)
                                    <a href="{{ route('admin.categories.show', $category->parent) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Image:</th>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" width="120" alt="Category Image">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Subcategories --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Subcategories</h5>
                    <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->category_id }}"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Subcategory
                    </a>
                </div>
                <div class="card-body">
                    @if($category->children->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->children as $child)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.categories.show', $child) }}">
                                                    {{ $child->name }}
                                                </a>
                                            </td>
                                            <td>{{ $child->slug }}</td>
                                            <td>
                                                <span class="badge bg-{{ $child->status ? 'success' : 'secondary' }}">
                                                    {{ $child->status ? 'Visible' : 'Hidden' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', $child) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $child) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No subcategories found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
