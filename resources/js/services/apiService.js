import axios from 'axios';

// Tạo instance axios với cấu hình cơ bản
const apiClient = axios.create({
    baseURL: '/api', // URL cơ bản cho API
    timeout: 10000, // Timeout 10 giây
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Interceptor để thêm token vào header
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

// Interceptor để xử lý response
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401) {
            // Token hết hạn, redirect về login
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

class ApiService {
    // ===== AUTHENTICATION =====
    static async login(credentials) {
        try {
            const response = await apiClient.post('/auth/login', credentials);
            if (response.data.token) {
                localStorage.setItem('auth_token', response.data.token);
            }
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async logout() {
        try {
            await apiClient.post('/auth/logout');
            localStorage.removeItem('auth_token');
        } catch (error) {
            console.error('Logout error:', error);
        }
    }

    static async register(userData) {
        try {
            const response = await apiClient.post('/auth/register', userData);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // ===== ORDERS API =====
    static async getOrders() {
        try {
            const response = await apiClient.get('/admin/orders');
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async getOrder(orderId) {
        try {
            const response = await apiClient.get(`/admin/orders/${orderId}`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async updateOrder(orderId, orderData) {
        try {
            const response = await apiClient.put(`/admin/orders/${orderId}`, orderData);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async deleteOrder(orderId) {
        try {
            const response = await apiClient.delete(`/admin/orders/${orderId}`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async getTrashedOrders() {
        try {
            const response = await apiClient.get('/admin/orders/trashed');
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async restoreOrder(orderId) {
        try {
            const response = await apiClient.post(`/admin/orders/${orderId}/restore`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async forceDeleteOrder(orderId) {
        try {
            const response = await apiClient.delete(`/admin/orders/${orderId}/force-delete`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async updateOrderStatus(orderId, status) {
        try {
            const response = await apiClient.post(`/admin/orders/${orderId}/update-status`, { status });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // ===== RETURNS API =====
    static async getReturns() {
        try {
            const response = await apiClient.get('/admin/orders/returns');
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async processReturn(returnId, returnData) {
        try {
            const response = await apiClient.post(`/admin/orders/returns/${returnId}/process`, returnData);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // ===== SHIPPING API =====
    static async calculateShippingFee(orderId, shippingData) {
        try {
            const response = await apiClient.post(`/shipping-fee/${orderId}`, shippingData);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    static async testGHNShippingFee() {
        try {
            const response = await apiClient.get('/test-ghn-shipping-fee');
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // ===== UTILITY METHODS =====
    static async ping() {
        try {
            const response = await apiClient.get('/ping');
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Xử lý lỗi chung
    static handleError(error) {
        if (error.response) {
            // Server trả về response với status code lỗi
            const { status, data } = error.response;
            return {
                status,
                message: data.message || 'Đã xảy ra lỗi',
                errors: data.errors || null,
                data: data.data || null
            };
        } else if (error.request) {
            // Request được gửi nhưng không nhận được response
            return {
                status: 0,
                message: 'Không thể kết nối đến server',
                errors: null,
                data: null
            };
        } else {
            // Lỗi khác
            return {
                status: 0,
                message: error.message || 'Đã xảy ra lỗi không xác định',
                errors: null,
                data: null
            };
        }
    }

    // Kiểm tra trạng thái đăng nhập
    static isAuthenticated() {
        return !!localStorage.getItem('auth_token');
    }

    // Lấy token hiện tại
    static getToken() {
        return localStorage.getItem('auth_token');
    }
}

export default ApiService;
