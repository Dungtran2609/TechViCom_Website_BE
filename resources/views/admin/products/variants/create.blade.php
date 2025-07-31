@extends('admin.layouts.app')

@section('title', 'Thêm biến thể sản phẩm')

@section('content')
<div class="container">
    <h2>Thêm biến thể cho: {{ $product->name }}</h2>

    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Các input như price, stock, image,... -->
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Số lượng tồn kho</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Khối lượng (gram)</label>
            <input type="number" name="weight" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label>Kích thước (VD: 10x20x5 cm)</label>
            <input type="text" name="dimensions" class="form-control">
        </div>

        <div class="mb-3">
            <label>Ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Chọn thuộc tính động -->
        <div class="mb-3">
            <label>Chọn thuộc tính</label>
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

        <button class="btn btn-success">Thêm mới</button>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
    </form>
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

        let html = `<label>${attrName}</label>`;
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
