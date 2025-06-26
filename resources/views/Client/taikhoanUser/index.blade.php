<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .profile-info {
            background: white;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .profile-info p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .edit-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .edit-link a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .edit-link a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <a href="{{ route('client.home') }}" style="display: inline-block; margin-bottom: 20px; color: #007bff;">&larr; Quay
        lại trang chủ</a>

    <div class="profile-info">
        <h2>Thông tin cá nhân</h2>

        <p><span class="label">Họ tên:</span> {{ $user->name }}</p>
        <p><span class="label">Email:</span> {{ $user->email }}</p>
        <p><span class="label">Số điện thoại:</span> {{ $profile->phone ?? 'Chưa cập nhật' }}</p>
        <p><span class="label">Địa chỉ:</span>
            {{ $profile->street ?? '' }},
            {{ $profile->ward ?? '' }},
            {{ $profile->district ?? '' }},
            {{ $profile->province ?? '' }}
        </p>
        <p><span class="label">Ngày sinh:</span> {{ $profile->birthday ?? 'Chưa cập nhật' }}</p>
        <p><span class="label">Giới tính:</span>
            @if ($profile && $profile->gender)
                @switch($profile->gender)
                    @case('male')
                        Nam
                    @break

                    @case('female')
                        Nữ
                    @break

                    @case('other')
                        Khác
                    @break

                    @default
                        Chưa cập nhật
                @endswitch
            @else
                Chưa cập nhật
            @endif
        </p>


        <div class="edit-link">
            <a href="{{ route('client.profile.edit') }}">Chỉnh sửa thông tin</a>
        </div>
    </div>
</body>

</html>
