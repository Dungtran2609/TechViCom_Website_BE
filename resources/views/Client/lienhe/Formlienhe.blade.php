<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .contact-form {
            background: white;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-form h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .contact-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contact-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }
        .contact-form button:hover {
            background-color: #218838;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

    <a href="{{ route('client.home') }}" style="display: inline-block; margin-bottom: 20px; color: #007bff;">
        &larr; Quay lại trang chủ
    </a>

    <div class="contact-form">
        <h2>Liên hệ với chúng tôi</h2>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="error-message">
                <ul style="padding-left: 20px; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.lienhe.store') }}" method="POST">
            @csrf

            <label for="name">Họ tên</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="error-message">{{ $message }}</div> @enderror

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error-message">{{ $message }}</div> @enderror

            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            @error('phone') <div class="error-message">{{ $message }}</div> @enderror

            <label for="subject">Tiêu đề</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
            @error('subject') <div class="error-message">{{ $message }}</div> @enderror

            <label for="message">Nội dung</label>
            <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
            @error('message') <div class="error-message">{{ $message }}</div> @enderror

            <button type="submit">Gửi liên hệ</button>
        </form>
    </div>

</body>
</html>
