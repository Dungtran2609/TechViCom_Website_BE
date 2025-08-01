@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart) || count($cart) === 0)
        <p>Giỏ hàng trống. <a href="{{ route('products.show', 1) }}" class="text-blue-600 underline">Tiếp tục mua sắm</a></p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left">Sản phẩm</th>
                        <th class="p-3 text-center">Giá</th>
                        <th class="p-3 text-center">Số lượng</th>
                        <th class="p-3 text-center">Thành tiền</th>
                        <th class="p-3 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($cart as $id => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b">
                            <td class="p-3 flex items-center gap-4">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                <div>
                                    <div class="font-semibold">{{ $item['name'] }}</div>
                                </div>
                            </td>
                            <td class="p-3 text-center">{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                    <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Cập nhật</button>
                                </form>
                            </td>
                            <td class="p-3 text-center">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('cart.remove', $id) }}" 
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" 
                                   class="text-red-600 underline">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="p-3 text-right font-bold">Tổng cộng:</td>
                        <td class="p-3 text-center font-bold">{{ number_format($total, 0, ',', '.') }}₫</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded hover:bg-gray-100">Tiếp tục mua sắm</a>
            <div class="text-right">
                <div class="text-xl font-bold mb-2">Tổng: {{ number_format($total, 0, ',', '.') }}₫</div>
                <a href="#" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">Tiến hành thanh toán</a>
            </div>
        </div>
    @endif
</div>
@endsection
