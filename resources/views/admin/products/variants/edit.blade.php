@extends('admin.layouts.app')

@section('title', 'Sửa biến thể sản phẩm')

@section('content')

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8 mt-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Sửa biến thể của: <span class="text-primary">{{ $product->name }}</span></h2>

    <form action="{{ route('admin.products.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium text-gray-700 mb-1">Giá <span class="text-red-500">*</span></label>
            <input type="number" name="price" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" value="{{ $variant->price }}" required>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" value="{{ $variant->sale_price }}">
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Số lượng tồn kho <span class="text-red-500">*</span></label>
            <input type="number" name="stock" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" value="{{ $variant->stock }}" required>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Khối lượng (gram)</label>
            <input type="number" name="weight" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" step="0.01" value="{{ $variant->weight }}">
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Kích thước</label>
            <input type="text" name="dimensions" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" value="{{ $variant->dimensions }}">
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Ảnh hiện tại:</label><br>
            @if ($variant->image)
                <img src="{{ asset('storage/' . $variant->image) }}" width="80" class="rounded shadow mb-2"><br>
            @else
                <span class="text-gray-500">Không có ảnh</span>
            @endif
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Ảnh mới (nếu muốn thay):</label>
            <input type="file" name="image" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div id="attribute-values-container">
            @foreach ($variant->attributesValue as $attrVal)
                <div class="mb-3">
                    <label class="block font-medium text-gray-700 mb-1">{{ $attrVal->attribute->name }}</label>
                    <select name="attribute_values[]" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
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

        <div>
            <label class="block font-medium text-gray-700 mb-1">Thêm thuộc tính</label>
            <select id="attribute-select" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
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

        <div class="flex gap-3 mt-6">
            <button class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 rounded shadow transition">Cập nhật</button>
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded shadow transition">Quay lại</a>
        </div>
    </form>
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

        if (!attrId || usedAttributeIds.has(attrId)) return;

        usedAttributeIds.add(parseInt(attrId));

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

        selectedOption.disabled = true;
        this.value = '';
    });
</script>
@endsection
