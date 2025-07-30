import React, { useState, useEffect } from 'react';
import ApiService from '../services/apiService';
import LoginForm from './Auth/LoginForm';
import OrderList from './Orders/OrderList';
import OrderForm from './Orders/OrderForm';

const App = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [currentView, setCurrentView] = useState('orders'); // orders, create, edit
    const [selectedOrderId, setSelectedOrderId] = useState(null);
    const [showLogin, setShowLogin] = useState(false);

    useEffect(() => {
        // Kiểm tra trạng thái đăng nhập khi component mount
        checkAuthStatus();
    }, []);

    const checkAuthStatus = () => {
        const authenticated = ApiService.isAuthenticated();
        setIsAuthenticated(authenticated);
        if (!authenticated) {
            setShowLogin(true);
        }
    };

    const handleLoginSuccess = (result) => {
        setIsAuthenticated(true);
        setShowLogin(false);
        console.log('Đăng nhập thành công:', result);
    };

    const handleLogout = async () => {
        try {
            await ApiService.logout();
            setIsAuthenticated(false);
            setShowLogin(true);
            setCurrentView('orders');
        } catch (error) {
            console.error('Lỗi đăng xuất:', error);
        }
    };

    const handleCreateOrder = () => {
        setCurrentView('create');
        setSelectedOrderId(null);
    };

    const handleEditOrder = (orderId) => {
        setCurrentView('edit');
        setSelectedOrderId(orderId);
    };

    const handleOrderSuccess = () => {
        setCurrentView('orders');
        setSelectedOrderId(null);
    };

    const handleCancel = () => {
        setCurrentView('orders');
        setSelectedOrderId(null);
    };

    // Hiển thị form đăng nhập nếu chưa đăng nhập
    if (showLogin) {
        return (
            <LoginForm
                onSuccess={handleLoginSuccess}
                onRegisterClick={() => console.log('Chuyển đến trang đăng ký')}
            />
        );
    }

    return (
        <div className="min-h-screen bg-gray-100">
            {/* Header */}
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center py-6">
                        <div className="flex items-center">
                            <h1 className="text-2xl font-bold text-gray-900">
                                TechViCom Admin
                            </h1>
                        </div>
                        <div className="flex items-center space-x-4">
                            <span className="text-gray-700">
                                Xin chào, Admin!
                            </span>
                            <button
                                onClick={handleLogout}
                                className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                            >
                                Đăng xuất
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {/* Navigation */}
            <nav className="bg-white border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex space-x-8">
                        <button
                            onClick={() => setCurrentView('orders')}
                            className={`py-4 px-1 border-b-2 font-medium text-sm ${
                                currentView === 'orders'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }`}
                        >
                            Quản lý đơn hàng
                        </button>
                        <button
                            onClick={handleCreateOrder}
                            className={`py-4 px-1 border-b-2 font-medium text-sm ${
                                currentView === 'create'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }`}
                        >
                            Tạo đơn hàng mới
                        </button>
                    </div>
                </div>
            </nav>

            {/* Main Content */}
            <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                {currentView === 'orders' && (
                    <OrderList onEditOrder={handleEditOrder} />
                )}

                {currentView === 'create' && (
                    <OrderForm
                        onSuccess={handleOrderSuccess}
                        onCancel={handleCancel}
                    />
                )}

                {currentView === 'edit' && selectedOrderId && (
                    <OrderForm
                        orderId={selectedOrderId}
                        onSuccess={handleOrderSuccess}
                        onCancel={handleCancel}
                    />
                )}
            </main>

            {/* Footer */}
            <footer className="bg-white border-t">
                <div className="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <p className="text-center text-gray-500 text-sm">
                        © 2024 TechViCom. Tất cả quyền được bảo lưu.
                    </p>
                </div>
            </footer>
        </div>
    );
};

export default App;
