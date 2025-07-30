<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TechViCom Admin - Quản lý đơn hàng</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Meta tags -->
    <meta name="description" content="Hệ thống quản lý đơn hàng TechViCom">
    <meta name="keywords" content="quản lý đơn hàng, admin, techvicom">
    <meta name="author" content="TechViCom">

    <!-- Open Graph -->
    <meta property="og:title" content="TechViCom Admin">
    <meta property="og:description" content="Hệ thống quản lý đơn hàng TechViCom">
    <meta property="og:type" content="website">

    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/main.jsx'])
</head>
<body>
    <!-- Loading screen -->
    <div id="loading-screen" class="fixed inset-0 bg-white flex items-center justify-center z-50">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Đang tải ứng dụng...</p>
        </div>
    </div>

    <!-- React app container -->
    <div id="app"></div>

    <!-- Global error handler -->
    <script>
        // Xử lý lỗi toàn cục
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
        });

        // Xử lý unhandled promise rejections
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Unhandled promise rejection:', e.reason);
        });

        // Ẩn loading screen khi React app đã load
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen) {
                    loadingScreen.style.opacity = '0';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                    }, 300);
                }
            }, 1000);
        });

        // CSRF token cho Axios
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

    <!-- NoScript fallback -->
    <noscript>
        <div class="min-h-screen flex items-center justify-center bg-gray-100">
            <div class="text-center p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    JavaScript bị vô hiệu hóa
                </h1>
                <p class="text-gray-600 mb-4">
                    Ứng dụng này yêu cầu JavaScript để hoạt động. Vui lòng bật JavaScript trong trình duyệt của bạn.
                </p>
                <a href="/" class="text-blue-600 hover:text-blue-800">
                    Tải lại trang
                </a>
            </div>
        </div>
    </noscript>
</body>
</html>
