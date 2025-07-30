@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách mã giảm giá</h2>
        <a href="{{ route('admin.coupons.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow border border-gray-400">
            + Thêm mã mới
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm rounded-lg">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-left">
                    <th class="px-4 py-2 border-b">Mã</th>
                    <th class="px-4 py-2 border-b">Kiểu</th>
                    <th class="px-4 py-2 border-b">Giá trị</th>
                    <th class="px-4 py-2 border-b">Ngày bắt đầu</th>
                    <th class="px-4 py-2 border-b">Ngày kết thúc</th>
                    <th class="px-4 py-2 border-b">Trạng thái</th>
                    <th class="px-4 py-2 border-b text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @foreach ($coupons as $coupon)
                    @php
                        $typeMapping = ['percent' => 'Phần trăm', 'amount' => 'Cố định'];
                    @endphp
                    <tr class="hover:bg-gray-50 border-b {{ $coupon->trashed() ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-2">{{ $coupon->code }}</td>
                        <td class="px-4 py-2">{{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}</td>
                        <td class="px-4 py-2">
                            {{ $coupon->discount_type === 'percent' 
                                ? $coupon->value . '%' 
                                : number_format($coupon->value, 0, ',', '.') . '₫' }}
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            @if ($coupon->status)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Kích hoạt</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Tạm dừng</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center space-x-1">
                            @if ($coupon->trashed())
                                {{-- Nút Khôi phục --}}
                                <form action="{{ route('admin.coupons.restore', $coupon->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                        Khôi phục
                                    </button>
                                </form>

                                {{-- Nút Xoá vĩnh viễn (force delete) --}}
                                <form action="{{ route('admin.coupons.forceDelete', $coupon->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xoá vĩnh viễn?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                        Xoá vĩnh viễn
                                    </button>
                                </form>
                            @else
                                {{-- Nút Sửa --}}
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                   class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold">
                                    Sửa
                                </a>

                                {{-- Nút Xoá mềm --}}
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline-block"
      onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" value="{{ $coupon->id }}">
    <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
        Xoá
    </button>
</form>

                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
