import React, { useState, useEffect } from 'react';
import ApiService from '../../services/apiService';
import { useApiForm } from '../../hooks/useApi';

const OrderForm = ({ orderId = null, onSuccess, onCancel }) => {
    const [formData, setFormData] = useState({
        recipient_name: '',
        recipient_phone: '',
        recipient_address: '',
        province_id: '',
        district_id: '',
        ward_id: '',
        shipping_fee: 0,
        payment_method: 'cod',
        status: 'pending',
        shipping_method_id: 1,
        coupon_id: null
    });

    const [shippingFee, setShippingFee] = useState(0);
    const [isEditing, setIsEditing] = useState(false);

    const { loading, error, success, submit, reset } = useApiForm(
        orderId ?
        (data) => ApiService.updateOrder(orderId, data) :
        (data) => ApiService.createOrder(data)
    );

    useEffect(() => {
        if (orderId) {
            setIsEditing(true);
            loadOrderData();
        }
    }, [orderId]);

    const loadOrderData = async () => {
        try {
            const order = await ApiService.getOrder(orderId);
            setFormData({
                recipient_name: order.recipient_name || '',
                recipient_phone: order.recipient_phone || '',
                recipient_address: order.recipient_address || '',
                province_id: order.province_id || '',
                district_id: order.district_id || '',
                ward_id: order.ward_id || '',
                shipping_fee: order.shipping_fee || 0,
                payment_method: order.payment_method || 'cod',
                status: order.status || 'pending',
                shipping_method_id: order.shipping_method_id || 1,
                coupon_id: order.coupon_id || null
            });
        } catch (error) {
            console.error('Lỗi tải dữ liệu đơn hàng:', error);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await submit(formData);
            if (onSuccess) {
                onSuccess();
            }
        } catch (error) {
            console.error('Lỗi submit form:', error);
        }
    };

    const calculateShipping = async () => {
        if (!orderId) return;

        try {
            const result = await ApiService.calculateShippingFee(orderId, {
                province_id: formData.province_id,
                district_id: formData.district_id,
                ward_id: formData.ward_id
            });
            setShippingFee(result.shipping_fee || 0);
            setFormData(prev => ({
                ...prev,
                shipping_fee: result.shipping_fee || 0
            }));
        } catch (error) {
            console.error('Lỗi tính phí vận chuyển:', error);
        }
    };

    if (success) {
        return (
            <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <strong>Thành công!</strong> {isEditing ? 'Đơn hàng đã được cập nhật.' : 'Đơn hàng đã được tạo.'}
            </div>
        );
    }

    return (
        <div className="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <h2 className="text-2xl font-bold mb-6">
                {isEditing ? 'Chỉnh sửa đơn hàng' : 'Tạo đơn hàng mới'}
            </h2>

            {error && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Lỗi:</strong> {error.message}
                </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6">
                {/* Thông tin khách hàng */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Tên khách hàng *
                        </label>
                        <input
                            type="text"
                            name="recipient_name"
                            value={formData.recipient_name}
                            onChange={handleInputChange}
                            required
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Số điện thoại *
                        </label>
                        <input
                            type="tel"
                            name="recipient_phone"
                            value={formData.recipient_phone}
                            onChange={handleInputChange}
                            required
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                {/* Địa chỉ */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                        Địa chỉ chi tiết *
                    </label>
                    <textarea
                        name="recipient_address"
                        value={formData.recipient_address}
                        onChange={handleInputChange}
                        required
                        rows="3"
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                {/* Tỉnh/Thành phố, Quận/Huyện, Phường/Xã */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Tỉnh/Thành phố
                        </label>
                        <select
                            name="province_id"
                            value={formData.province_id}
                            onChange={handleInputChange}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Chọn tỉnh/thành phố</option>
                            <option value="1">Hà Nội</option>
                            <option value="2">TP. Hồ Chí Minh</option>
                            <option value="3">Đà Nẵng</option>
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Quận/Huyện
                        </label>
                        <select
                            name="district_id"
                            value={formData.district_id}
                            onChange={handleInputChange}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Phường/Xã
                        </label>
                        <select
                            name="ward_id"
                            value={formData.ward_id}
                            onChange={handleInputChange}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Chọn phường/xã</option>
                        </select>
                    </div>
                </div>

                {/* Phí vận chuyển */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Phí vận chuyển
                        </label>
                        <div className="flex">
                            <input
                                type="number"
                                name="shipping_fee"
                                value={formData.shipping_fee}
                                onChange={handleInputChange}
                                className="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            {isEditing && (
                                <button
                                    type="button"
                                    onClick={calculateShipping}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700"
                                >
                                    Tính phí
                                </button>
                            )}
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Phương thức thanh toán
                        </label>
                        <select
                            name="payment_method"
                            value={formData.payment_method}
                            onChange={handleInputChange}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="cod">Thanh toán khi nhận hàng</option>
                            <option value="banking">Chuyển khoản ngân hàng</option>
                            <option value="momo">Ví MoMo</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>
                </div>

                {/* Trạng thái đơn hàng */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                        Trạng thái đơn hàng
                    </label>
                    <select
                        name="status"
                        value={formData.status}
                        onChange={handleInputChange}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="pending">Chờ xử lý</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="shipped">Đã gửi hàng</option>
                        <option value="delivered">Đã giao hàng</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>

                {/* Buttons */}
                <div className="flex justify-end space-x-4">
                    <button
                        type="button"
                        onClick={onCancel}
                        className="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Hủy
                    </button>
                    <button
                        type="submit"
                        disabled={loading}
                        className="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                    >
                        {loading ? 'Đang xử lý...' : (isEditing ? 'Cập nhật' : 'Tạo đơn hàng')}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default OrderForm;
