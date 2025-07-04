@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Chỉnh sửa thông tin tài khoản</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Họ tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
        </div>
        <div class="mb-3">
            <label>Ngày sinh</label>
            <input type="date" name="birthday" class="form-control" value="{{ old('birthday', $user->birthday) }}">
        </div>
        <div class="mb-3">
            <label>Giới tính</label>
            <select name="gender" class="form-control">
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Ảnh đại diện</label>
            <input type="file" name="image_profile" class="form-control">
            @if($user->image_profile)
                <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Avatar" width="80" class="mt-2">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('account.show') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection