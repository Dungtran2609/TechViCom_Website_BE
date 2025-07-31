@extends('admin.layouts.app')

@section('title', 'Sửa biến thể sản phẩm')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title mb-4">Sửa biến thể của: <span class="text-primary">{{ $product->name }}</span></h2>

            <form action="{{ route('admin.products.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Giá <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá khuyến mãi</label>
                    <input type="number" name="sale_price" class="form-control" value="{{ $variant->sale_price }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Khối lượng (gram)</label>
                    <input type="number" name="weight" class="form-control" step="0.01" value="{{ $variant->weight }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kích thước</label>
                    <input type="text" name="dimensions" class="form-control" value="{{ $variant->dimensions }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh hiện tại:</label><br>
                    @if ($variant->image)
                        <img src="{{ asset('storage/' . $variant->image) }}" width="80" class="img-thumbnail mb-2"><br>
                    @else
                        <span class="text-muted">Không có ảnh</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh mới (nếu muốn thay):</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div id="attribute-values-container">
                    @foreach ($variant->attributesValue as $attrVal)
                        <div class="mb-3">
                            <label class="form-label">{{ $attrVal->attribute->name }}</label>
                            <select name="attribute_values[]" class="form-select" required>
                                <option value="">-- Chọn giá trị --</option>
                                @foreach ($attrVal->attribute->values as $val)
                                    <option value="{{ $val->id }}" {{ $val->id == $attrVal->id ? 'selected' : '' }}>
                                        {{ $val->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label class="form-label">Thêm thuộc tính</label>
                    <select id="attribute-select" class="form-select">
                        <option value="">-- Chọn thuộc tính --</option>
                        @foreach ($attributes as $attribute)
                            <option 
                                value="{{ $attribute->id }}" 
                                data-values='@json($attribute->values)'
                                data-name="{{ $attribute->name }}"
                                {{ in_array($attribute->id, $variant->attributesValue->pluck('attribute_id')->toArray()) ? 'disabled' : '' }}
                            >
                                {{ $attribute->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const attributeSelect = document.getElementById('attribute-select');
    const container = document.getElementById('attribute-values-container');
    const usedAttributeIds = new Set(@json($variant->attributesValue->pluck('attribute_id')));

    attributeSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const attrId = this.value;
        const attrName = selectedOption.dataset.name;
        const values = JSON.parse(selectedOption.dataset.values || '[]');

        if (!attrId || usedAttributeIds.has(parseInt(attrId))) return;

        usedAttributeIds.add(parseInt(attrId));

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

        selectedOption.disabled = true;
        this.value = '';
    });
</script>
@endsection
