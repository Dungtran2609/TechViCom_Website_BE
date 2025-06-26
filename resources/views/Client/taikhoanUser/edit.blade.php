<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Thông tin cá nhân</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 30px; }
        .profile-form {
            background: white; padding: 20px; max-width: 600px; margin: auto;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-form h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .profile-form label { display: block; margin-bottom: 5px; font-weight: bold; }
        .profile-form input, .profile-form select {
            width: 100%; padding: 8px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        .profile-form button {
            background-color: #007bff; color: white; border: none;
            padding: 10px 20px; border-radius: 5px; width: 100%;
        }
        .profile-form button:hover { background-color: #0056b3; }
        .alert {
            background: #d4edda; color: #155724; padding: 10px;
            border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
<div class="profile-form">
    <h2>Thông tin cá nhân</h2>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form action="{{ route('client.profile.update') }}" method="POST" id="profile-form">
        @csrf

        <label for="name">Họ tên</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>

        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}">

        <label for="province">Tỉnh/Thành</label>
        <select name="province" id="province" required>
            <option value="">-- Chọn tỉnh/thành --</option>
            @foreach ($provinces as $province)
                <option value="{{ $province['code'] }}"
                    {{ old('province', $selectedProvinceCode ?? '') == $province['code'] ? 'selected' : '' }}>
                    {{ $province['name'] }}
                </option>
            @endforeach
        </select>

        <label for="district">Quận/Huyện</label>
        <select name="district" id="district" required>
            <option value="">-- Chọn quận/huyện --</option>
            {{-- Các option sẽ được load ajax --}}
        </select>

        <label for="ward">Phường/Xã</label>
        <select name="ward" id="ward" required>
            <option value="">-- Chọn phường/xã --</option>
            {{-- Các option sẽ được load ajax --}}
        </select>

        <label for="street">Đường</label>
        <input type="text" id="street" name="street" value="{{ old('street', $profile->street ?? '') }}">

        <label for="birthday">Ngày sinh</label>
        <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $profile->birthday ?? '') }}">

        <label for="gender">Giới tính</label>
        <select name="gender" id="gender">
            <option value="">-- Chọn giới tính --</option>
            <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
            <option value="other" {{ old('gender', $profile->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
        </select>

        <button type="submit">Cập nhật</button>
        <a href="{{ route('client.profile.index') }}" style="display: block; text-align: center; margin-top: 10px;">Quay lại</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    // Giá trị code được chọn trước đó (nếu có)
    const selectedProvince = "{{ old('province', $selectedProvinceCode ?? '') }}";
    const selectedDistrict = "{{ old('district', $selectedDistrictCode ?? '') }}";
    const selectedWard = "{{ old('ward', $profile->ward_code ?? '') }}";

    // Hàm load danh sách huyện theo tỉnh code
    async function loadDistricts(provinceCode, selectedDistrictCode = null) {
        districtSelect.innerHTML = '<option value="">-- Đang tải quận/huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

        if (!provinceCode) {
            districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            return;
        }

        try {
            const response = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
            const data = await response.json();

            const districts = data.districts || [];

            let html = '<option value="">-- Chọn quận/huyện --</option>';
            districts.forEach(district => {
                html += `<option value="${district.code}" ${district.code == selectedDistrictCode ? 'selected' : ''}>${district.name}</option>`;
            });
            districtSelect.innerHTML = html;

            if (selectedDistrictCode) {
                await loadWards(selectedDistrictCode, selectedWard);
            }
        } catch (error) {
            districtSelect.innerHTML = '<option value="">-- Lỗi tải quận/huyện --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            console.error(error);
        }
    }

    // Hàm load danh sách xã theo huyện code
    async function loadWards(districtCode, selectedWardCode = null) {
        wardSelect.innerHTML = '<option value="">-- Đang tải phường/xã --</option>';

        if (!districtCode) {
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            return;
        }

        try {
            const response = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
            const data = await response.json();

            const wards = data.wards || [];

            let html = '<option value="">-- Chọn phường/xã --</option>';
            wards.forEach(ward => {
                html += `<option value="${ward.code}" ${ward.code == selectedWardCode ? 'selected' : ''}>${ward.name}</option>`;
            });
            wardSelect.innerHTML = html;
        } catch (error) {
            wardSelect.innerHTML = '<option value="">-- Lỗi tải phường/xã --</option>';
            console.error(error);
        }
    }

    // Event change province
    provinceSelect.addEventListener('change', () => {
        loadDistricts(provinceSelect.value);
        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
    });

    // Event change district
    districtSelect.addEventListener('change', () => {
        loadWards(districtSelect.value);
    });

    // Khởi tạo dữ liệu khi load trang (nếu có dữ liệu đã chọn)
    if (selectedProvince) {
        loadDistricts(selectedProvince, selectedDistrict);
    }
    if (selectedDistrict) {
        loadWards(selectedDistrict, selectedWard);
    }
});
</script>
</body>
</html>
