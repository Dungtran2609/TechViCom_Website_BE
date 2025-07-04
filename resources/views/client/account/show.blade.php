@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Thông tin tài khoản</h3>
    <table class="table table-bordered">
        <tr>
            <th>Họ tên</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{{ $user->phone_number }}</td>
        </tr>
        <tr>
            <th>Ngày sinh</th>
            <td>{{ $user->birthday }}</td>
        </tr>
        <tr>
            <th>Giới tính</th>
            <td>{{ $user->gender }}</td>
        </tr>
        <tr>
            <th>Ảnh đại diện</th>
            <td>
                @if($user->image_profile)
                    <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Avatar" width="80">
                @else
                    Không có ảnh
                @endif
            </td>
        </tr>
    </table>
    <a href="{{ route('account.edit') }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
@endsection