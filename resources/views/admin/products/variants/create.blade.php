@extends('admin.layouts.app')

@section('title', 'Thêm biến thể sản phẩm')

@section('content')

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title mb-4">Thêm biến thể cho: <span class="text-primary">{{ $product->name }}</span></h2>

            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($product->type === 'variable')
                    <div class="mb-3">
                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Khối lượng (gram)</label>
                    <input type="number" name="weight" step="0.01" class="form-control" value="{{ old('weight') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kích thước (VD: 10x20x5 cm)</label>
                    <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}">
                </div>
                <!-- Chọn thuộc tính động -->
                <div class="mb-3">
                    <label class="form-label">Chọn thuộc tính</label>
                    <select id="attribute-select" class="form-select">
                        <option value="">-- Chọn thuộc tính --</option>
                        @foreach ($attributes as $attribute)
                            <option value="{{ $attribute->id }}" data-values='@json($attribute->values)'>
                                {{ $attribute->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="attribute-values-container"></div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">Thêm mới</button>
                    <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const attributeSelect = document.getElementById('attribute-select');
    const container = document.getElementById('attribute-values-container');
    const usedAttributeIds = new Set();

    attributeSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const attrId = this.value;
        const attrName = selectedOption.textContent;
        const values = JSON.parse(selectedOption.dataset.values || '[]');

        if (!attrId || usedAttributeIds.has(attrId)) return;

        usedAttributeIds.add(attrId);

        // Tạo phần chọn giá trị
        const wrapper = document.createElement('div');
        wrapper.classList.add('mb-3');

        let html = `<label class="form-label">${attrName}</label>`;
        html += `<select name="attribute_values[]" class="form-select" required>`;
        html += `<option value="">-- Chọn giá trị --</option>`;
        for (const value of values) {
            html += `<option value="${value.id}">${value.value}</option>`;
        }
        html += `</select>`;

        wrapper.innerHTML = html;
        container.appendChild(wrapper);

        // Ẩn thuộc tính đã chọn khỏi dropdown
        selectedOption.disabled = true;
        this.value = '';
    });
</script>

@endsection