@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Breadcrumb / back --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-sm text-blue-600 underline">&larr; Quay lại</a>
    </div>

    <div class="flex flex-wrap gap-6">
        {{-- Ảnh sản phẩm --}}
        <div class="w-full md:w-1/2">
            <img 
                src="{{ $product->image ?: asset('images/placeholder.png') }}" 
                alt="{{ $product->name }}" 
                class="w-full h-auto object-cover rounded"
                onerror="this.src='{{ asset('images/placeholder.png') }}'"
            >
        </div>

        {{-- Thông tin --}}
        <div class="w-full md:w-1/2">
            {{-- Tên --}}
            <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>

            {{-- Danh mục và ngày --}}
            <tr>
    <th>Ngày tạo:</th>
    <td class="text-muted">
        {{ optional($product->created_at)->format('H:i, d/m/Y') ?: '—' }}
    </td>
</tr>
<tr>
    <th>Lần cập nhật cuối:</th>
    <td class="text-muted">
        {{ optional($product->updated_at)->format('H:i, d/m/Y') ?: '—' }}
    </td>
</tr>


            {{-- Giá --}}
            <p class="text-xl text-red-600 font-semibold mb-4">{{ number_format($product->price, 0, ',', '.') }}₫</p>

            {{-- Mô tả --}}
            <p class="mb-4">{{ $product->description ?? 'Chưa có mô tả.' }}</p>

            {{-- Form thêm vào giỏ --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4 max-w-md">
                @csrf

                {{-- Số lượng --}}
                <div class="flex items-center gap-2 mb-2">
                    <label for="quantity" class="mr-2">Số lượng:</label>
                    <input 
                        type="number" 
                        name="quantity" 
                        id="quantity" 
                        value="{{ old('quantity', 1) }}" 
                        min="1" 
                        class="border rounded px-2 py-1 w-20"
                    >
                </div>
                @if($errors->has('quantity'))
                    <div class="text-red-600 text-sm mb-2">{{ $errors->first('quantity') }}</div>
                @endif

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Thêm vào giỏ hàng
                </button>
            </form>

            {{-- Link xem giỏ --}}
            <div class="mt-2">
                <a href="{{ route('cart.index') }}" class="text-sm bg-gray-100 px-3 py-2 rounded inline-block hover:bg-gray-200">
                    Xem giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
