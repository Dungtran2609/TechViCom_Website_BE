<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'email', 'phone_number', 'image_profile', 'is_active', 'birthday', 'gender'
    ];

    /**
     * Các trường cần ẩn (không trả về khi lấy dữ liệu)
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Các trường sẽ được chuyển đổi thành kiểu dữ liệu Carbon (để dễ dàng thao tác với thời gian)
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'birthday',
    ];

    /**
     * Mối quan hệ người dùng và các vai trò
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Mối quan hệ người dùng và các địa chỉ
     */
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Mối quan hệ người dùng và các hoạt động
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Mối quan hệ người dùng và các thông báo
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications');
    }

    /**
     * Mối quan hệ người dùng và bài viết
     */
    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Mối quan hệ người dùng và các giỏ hàng
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Mối quan hệ người dùng và các bình luận sản phẩm
     */
    public function productComments()
    {
        return $this->hasMany(ProductComment::class);
    }

    /**
     * Mối quan hệ người dùng và các đánh giá sản phẩm
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Mối quan hệ người dùng và các đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Mối quan hệ người dùng và các lịch sử đăng nhập
     */
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Thêm thông báo cho người dùng
     */
    public function addNotification($notification)
    {
        return $this->notifications()->attach($notification);
    }

    /**
     * Hàm xác thực mật khẩu của người dùng
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password); // Dùng Hash để so sánh mật khẩu đã mã hóa
    }

    /**
     * Thêm vai trò cho người dùng
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->roles()->attach($role);
    }

    /**
     * Xóa vai trò khỏi người dùng
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles()->detach($role);
    }

    /**
     * Kiểm tra xem người dùng có vai trò nhất định không
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Tạo mật khẩu mới cho người dùng và mã hóa trước khi lưu
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password); // Mã hóa mật khẩu khi lưu vào CSDL
    }
}
