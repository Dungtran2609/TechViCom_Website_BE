# Hướng dẫn sử dụng React API với Laravel 12 Backend

## Tổng quan

Dự án này tích hợp ReactJS frontend với Laravel 12 backend thông qua RESTful API. Hệ thống bao gồm:

- **Backend**: Laravel 12 với API endpoints
- **Frontend**: ReactJS với Axios cho HTTP requests
- **Authentication**: JWT/Sanctum token-based
- **UI Framework**: Tailwind CSS
- **Build Tool**: Vite

## Cấu trúc thư mục

```
resources/
├── js/
│   ├── services/
│   │   └── apiService.js          # API service chính
│   ├── hooks/
│   │   └── useApi.js              # Custom React hooks
│   ├── components/
│   │   ├── App.jsx                # Component chính
│   │   ├── Auth/
│   │   │   └── LoginForm.jsx      # Form đăng nhập
│   │   └── Orders/
│   │       ├── OrderList.jsx      # Danh sách đơn hàng
│   │       └── OrderForm.jsx      # Form tạo/sửa đơn hàng
│   └── main.jsx                   # Entry point
├── css/
│   └── app.css                    # Styles chính
└── views/
    └── app.blade.php              # Blade template cho React app
```

## Cài đặt và chạy

### 1. Cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install

# Cài đặt thêm React dependencies
npm install react react-dom
```

### 2. Cấu hình môi trường

```bash
# Copy file môi trường
cp .env.example .env

# Tạo key ứng dụng
php artisan key:generate

# Chạy migrations
php artisan migrate

# Tạo storage link
php artisan storage:link
```

### 3. Chạy ứng dụng

```bash
# Terminal 1: Chạy Laravel server
php artisan serve

# Terminal 2: Chạy Vite dev server
npm run dev

# Terminal 3: Build production (tùy chọn)
npm run build
```

### 4. Truy cập ứng dụng

- **Laravel Admin**: `http://localhost:8000/admin-control`
- **React Admin**: `http://localhost:8000/react-admin`

## API Service (apiService.js)

### Cấu hình cơ bản

```javascript
import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});
```

### Interceptors

#### Request Interceptor
```javascript
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);
```

#### Response Interceptor
```javascript
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
```

### Các methods API

#### Authentication
```javascript
// Đăng nhập
const login = async (credentials) => {
    const response = await ApiService.login(credentials);
    return response;
};

// Đăng xuất
const logout = async () => {
    await ApiService.logout();
};

// Đăng ký
const register = async (userData) => {
    const response = await ApiService.register(userData);
    return response;
};
```

#### Orders Management
```javascript
// Lấy danh sách đơn hàng
const getOrders = async () => {
    const response = await ApiService.getOrders();
    return response;
};

// Lấy chi tiết đơn hàng
const getOrder = async (orderId) => {
    const response = await ApiService.getOrder(orderId);
    return response;
};

// Cập nhật đơn hàng
const updateOrder = async (orderId, orderData) => {
    const response = await ApiService.updateOrder(orderId, orderData);
    return response;
};

// Xóa đơn hàng
const deleteOrder = async (orderId) => {
    const response = await ApiService.deleteOrder(orderId);
    return response;
};

// Cập nhật trạng thái đơn hàng
const updateOrderStatus = async (orderId, status) => {
    const response = await ApiService.updateOrderStatus(orderId, status);
    return response;
};
```

#### Shipping
```javascript
// Tính phí vận chuyển
const calculateShipping = async (orderId, shippingData) => {
    const response = await ApiService.calculateShippingFee(orderId, shippingData);
    return response;
};

// Test GHN shipping
const testGHN = async () => {
    const response = await ApiService.testGHNShippingFee();
    return response;
};
```

## Custom Hooks (useApi.js)

### useApi Hook
```javascript
import { useApi } from '../hooks/useApi';

const MyComponent = () => {
    const { data, loading, error, execute, reset } = useApi(ApiService.getOrders);

    useEffect(() => {
        execute();
    }, [execute]);

    if (loading) return <div>Đang tải...</div>;
    if (error) return <div>Lỗi: {error.message}</div>;

    return (
        <div>
            {data?.map(item => (
                <div key={item.id}>{item.name}</div>
            ))}
        </div>
    );
};
```

### useApiList Hook
```javascript
import { useApiList } from '../hooks/useApi';

const OrderList = () => {
    const {
        items: orders,
        loading,
        error,
        pagination,
        fetchData,
        refresh
    } = useApiList(ApiService.getOrders);

    useEffect(() => {
        fetchData();
    }, [fetchData]);

    return (
        <div>
            {orders.map(order => (
                <div key={order.id}>{order.recipient_name}</div>
            ))}
        </div>
    );
};
```

### useApiForm Hook
```javascript
import { useApiForm } from '../hooks/useApi';

const OrderForm = () => {
    const { loading, error, success, submit, reset } = useApiForm(
        (data) => ApiService.createOrder(data)
    );

    const handleSubmit = async (formData) => {
        try {
            await submit(formData);
            // Xử lý thành công
        } catch (error) {
            // Xử lý lỗi
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {/* Form fields */}
        </form>
    );
};
```

## Components

### OrderList Component
```javascript
import React from 'react';
import { useApiList } from '../hooks/useApi';
import ApiService from '../services/apiService';

const OrderList = () => {
    const {
        items: orders,
        loading,
        error,
        fetchData,
        refresh
    } = useApiList(ApiService.getOrders);

    const handleStatusUpdate = async (orderId, newStatus) => {
        try {
            await ApiService.updateOrderStatus(orderId, newStatus);
            refresh();
        } catch (error) {
            console.error('Lỗi cập nhật trạng thái:', error);
        }
    };

    return (
        <div>
            {/* Render orders list */}
        </div>
    );
};
```

### OrderForm Component
```javascript
import React, { useState, useEffect } from 'react';
import { useApiForm } from '../hooks/useApi';
import ApiService from '../services/apiService';

const OrderForm = ({ orderId = null, onSuccess, onCancel }) => {
    const [formData, setFormData] = useState({
        recipient_name: '',
        recipient_phone: '',
        // ... other fields
    });

    const { loading, error, success, submit } = useApiForm(
        orderId ? 
        (data) => ApiService.updateOrder(orderId, data) : 
        (data) => ApiService.createOrder(data)
    );

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await submit(formData);
            if (onSuccess) onSuccess();
        } catch (error) {
            console.error('Lỗi submit form:', error);
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {/* Form fields */}
        </form>
    );
};
```

## Error Handling

### API Error Structure
```javascript
{
    status: 422,
    message: "Validation failed",
    errors: {
        email: ["Email is required"],
        password: ["Password must be at least 8 characters"]
    },
    data: null
}
```

### Error Handling trong Components
```javascript
const handleApiCall = async () => {
    try {
        const result = await ApiService.someMethod();
        // Xử lý thành công
    } catch (error) {
        if (error.status === 422) {
            // Validation errors
            console.log('Validation errors:', error.errors);
        } else if (error.status === 401) {
            // Unauthorized
            console.log('Unauthorized access');
        } else {
            // Other errors
            console.log('Error:', error.message);
        }
    }
};
```

## Authentication

### Login Flow
```javascript
const handleLogin = async (credentials) => {
    try {
        const result = await ApiService.login(credentials);
        if (result.token) {
            localStorage.setItem('auth_token', result.token);
            // Redirect to dashboard
        }
    } catch (error) {
        console.error('Login failed:', error);
    }
};
```

### Check Authentication Status
```javascript
const isAuthenticated = ApiService.isAuthenticated();
const token = ApiService.getToken();
```

### Logout
```javascript
const handleLogout = async () => {
    try {
        await ApiService.logout();
        localStorage.removeItem('auth_token');
        // Redirect to login
    } catch (error) {
        console.error('Logout error:', error);
    }
};
```

## CORS Configuration

### Laravel CORS (config/cors.php)
```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

## Production Deployment

### 1. Build React App
```bash
npm run build
```

### 2. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## Troubleshooting

### Common Issues

1. **CORS Errors**
   - Kiểm tra cấu hình CORS trong Laravel
   - Đảm bảo API routes có prefix `/api`

2. **Authentication Issues**
   - Kiểm tra token trong localStorage
   - Verify API endpoints require authentication

3. **Build Errors**
   - Clear Vite cache: `rm -rf node_modules/.vite`
   - Reinstall dependencies: `npm install`

4. **API 404 Errors**
   - Kiểm tra route definitions
   - Verify API base URL configuration

### Debug Tips

```javascript
// Enable Axios debugging
apiClient.interceptors.request.use(request => {
    console.log('Request:', request);
    return request;
});

apiClient.interceptors.response.use(response => {
    console.log('Response:', response);
    return response;
});
```

## Best Practices

1. **Error Handling**: Luôn wrap API calls trong try-catch
2. **Loading States**: Hiển thị loading indicator cho user
3. **Validation**: Validate data trước khi gửi API
4. **Token Management**: Tự động refresh token khi cần
5. **Caching**: Cache data khi phù hợp
6. **Security**: Không lưu sensitive data trong localStorage

## API Endpoints Reference

### Orders
- `GET /api/admin/orders` - Lấy danh sách đơn hàng
- `GET /api/admin/orders/{id}` - Lấy chi tiết đơn hàng
- `PUT /api/admin/orders/{id}` - Cập nhật đơn hàng
- `DELETE /api/admin/orders/{id}` - Xóa đơn hàng
- `POST /api/admin/orders/{id}/update-status` - Cập nhật trạng thái

### Shipping
- `POST /api/shipping-fee/{orderId}` - Tính phí vận chuyển
- `GET /api/test-ghn-shipping-fee` - Test GHN API

### Authentication
- `POST /api/auth/login` - Đăng nhập
- `POST /api/auth/logout` - Đăng xuất
- `POST /api/auth/register` - Đăng ký

## Support

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra console browser
2. Kiểm tra Laravel logs: `storage/logs/laravel.log`
3. Verify API endpoints với Postman/Insomnia
4. Kiểm tra network tab trong DevTools 
