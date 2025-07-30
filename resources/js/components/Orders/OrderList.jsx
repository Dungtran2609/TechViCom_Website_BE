import React, { useEffect, useState } from 'react';
import ApiService from '../../services/apiService';
import { useApiList } from '../../hooks/useApi';

const OrderList = () => {
    const {
        items: orders,
        loading,
        error,
        fetchData,
        refresh
    } = useApiList(ApiService.getOrders);

    const [selectedOrder, setSelectedOrder] = useState(null);

    useEffect(() => {
        fetchData();
    }, [fetchData]);

    const handleStatusUpdate = async (orderId, newStatus) => {
        try {
            await ApiService.updateOrderStatus(orderId, newStatus);
            refresh(); // Refresh danh sách sau khi cập nhật
        } catch (error) {
            console.error('Lỗi cập nhật trạng thái:', error);
        }
    };

    const handleDelete = async (orderId) => {
        if (window.confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
            try {
                await ApiService.deleteOrder(orderId);
                refresh(); // Refresh danh sách sau khi xóa
            } catch (error) {
                console.error('Lỗi xóa đơn hàng:', error);
            }
        }
    };

    if (loading && orders.length === 0) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Lỗi:</strong> {error.message}
                <button
                    onClick={refresh}
                    className="ml-4 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                >
                    Thử lại
                </button>
            </div>
        );
    }

    return (
        <div className="container mx-auto px-4 py-8">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-3xl font-bold text-gray-900">Quản lý đơn hàng</h1>
                <button
                    onClick={refresh}
                    className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    disabled={loading}
                >
                    {loading ? 'Đang tải...' : 'Làm mới'}
                </button>
            </div>

            {loading && (
                <div className="flex justify-center py-4">
                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            )}

            <div className="bg-white shadow-md rounded-lg overflow-hidden">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tổng tiền
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {orders.map((order) => (
                            <tr key={order.id} className="hover:bg-gray-50">
                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{order.id}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {order.recipient_name}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(order.total_amount)}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap">
                                    <select
                                        value={order.status}
                                        onChange={(e) => handleStatusUpdate(order.id, e.target.value)}
                                        className="text-sm rounded-full px-3 py-1 border"
                                    >
                                        <option value="pending">Chờ xử lý</option>
                                        <option value="processing">Đang xử lý</option>
                                        <option value="shipped">Đã gửi hàng</option>
                                        <option value="delivered">Đã giao hàng</option>
                                        <option value="cancelled">Đã hủy</option>
                                    </select>
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {new Date(order.created_at).toLocaleDateString('vi-VN')}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div className="flex space-x-2">
                                        <button
                                            onClick={() => setSelectedOrder(order)}
                                            className="text-blue-600 hover:text-blue-900"
                                        >
                                            Chi tiết
                                        </button>
                                        <button
                                            onClick={() => handleDelete(order.id)}
                                            className="text-red-600 hover:text-red-900"
                                        >
                                            Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>

                {orders.length === 0 && !loading && (
                    <div className="text-center py-8 text-gray-500">
                        Không có đơn hàng nào
                    </div>
                )}
            </div>

            {/* Modal chi tiết đơn hàng */}
            {selectedOrder && (
                <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div className="mt-3">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">
                                Chi tiết đơn hàng #{selectedOrder.id}
                            </h3>
                            <div className="space-y-2">
                                <p><strong>Khách hàng:</strong> {selectedOrder.recipient_name}</p>
                                <p><strong>Số điện thoại:</strong> {selectedOrder.recipient_phone}</p>
                                <p><strong>Địa chỉ:</strong> {selectedOrder.recipient_address}</p>
                                <p><strong>Tổng tiền:</strong> {new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(selectedOrder.total_amount)}</p>
                                <p><strong>Trạng thái:</strong> {selectedOrder.status}</p>
                                <p><strong>Phương thức thanh toán:</strong> {selectedOrder.payment_method}</p>
                            </div>
                            <div className="mt-6 flex justify-end">
                                <button
                                    onClick={() => setSelectedOrder(null)}
                                    className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                                >
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default OrderList;
