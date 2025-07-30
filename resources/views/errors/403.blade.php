<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>403 - Không được phép</title>
    <style>
        body {
            background: linear-gradient(to right, #fceabb, #f8b500); /* nền vàng pastel */
            background-image: url('https://www.transparenttextures.com/patterns/food.png'); /* hoa văn cute */
            color: #2d3748;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        h1 {
            font-size: 5rem;
            margin-bottom: 0.5rem;
            color: #ff6b6b;
            text-shadow: 2px 2px #fff0f0;
        }
        p {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }
        img {
            width: 250px;
            max-width: 80%;
            margin-bottom: 20px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.5);
        }
        a {
            display: inline-block;
            padding: 10px 25px;
            background-color: #ff6ec7;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 12px;
            transition: background-color 0.3s, transform 0.2s;
        }
        a:hover {
            background-color: #ff4aa5;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <img src="https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExa2F0bmFtY2xwdnYwcHQ0czF4aXl2MXNpMmR1YmZxOGtiNG54Z2RoNCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/fnuSiwXMTV3zmYDf6k/giphy.gif" alt="???">
    <h1>403</h1>
    <p>Oops! Bạn không thể vào đây đâu... 🐰</p>
    <a href="{{ url('/') }}">Quay về trang chủ</a>
</body>
</html>
