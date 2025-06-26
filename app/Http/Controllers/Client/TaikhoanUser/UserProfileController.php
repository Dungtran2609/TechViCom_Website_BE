<!-- <?php

// namespace App\Http\Controllers\Client\TaikhoanUser;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use App\Models\UserProfile;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Http;

// class UserProfileController extends Controller
// {
//     // Hiển thị thông tin user và profile (chỉ user role khách hàng)
//     public function index()
//     {
//         $user = Auth::user();
//         if (!$user || $user->role !== 'khachhang') {
//             $user = User::where('role', 'khachhang')->first();
//         }

//         if (!$user) {
//             abort(404, 'Không tìm thấy user khách hàng');
//         }

//         $profile = $user->profile ?? null;

//         return view('client.taikhoanUser.index', compact('user', 'profile'));
//     }

//     // Hiển thị form sửa thông tin profile
//     public function edit()
//     {
//         $user = Auth::user();
//         if (!$user || $user->role !== 'khachhang') {
//             $user = User::where('role', 'khachhang')->first();
//         }

//         if (!$user) {
//             abort(404, 'Không tìm thấy user khách hàng');
//         }

//         $profile = $user->profile ?? new UserProfile();

//         // Lấy danh sách tỉnh/thành
//         $responseProvinces = Http::get('https://provinces.open-api.vn/api/p/');
//         $provinces = $responseProvinces->successful() ? $responseProvinces->json() : [];

//         // Xác định mã tỉnh đã chọn
//         $selectedProvinceCode = null;
//         if ($profile->province) {
//             foreach ($provinces as $province) {
//                 if ($province['name'] === $profile->province) {
//                     $selectedProvinceCode = $province['code'];
//                     break;
//                 }
//             }
//         }
//         if (!$selectedProvinceCode && !empty($provinces)) {
//             $selectedProvinceCode = $provinces[0]['code'];
//         }

//         // Lấy danh sách huyện của tỉnh đã chọn
//         $districts = [];
//         $selectedDistrictCode = null;
//         if ($selectedProvinceCode) {
//             $responseDistricts = Http::get("https://provinces.open-api.vn/api/p/{$selectedProvinceCode}?depth=2");
//             if ($responseDistricts->successful()) {
//                 $districts = $responseDistricts->json()['districts'] ?? [];

//                 if ($profile->district) {
//                     foreach ($districts as $district) {
//                         if ($district['name'] === $profile->district) {
//                             $selectedDistrictCode = $district['code'];
//                             break;
//                         }
//                     }
//                 }

//                 if (!$selectedDistrictCode && !empty($districts)) {
//                     $selectedDistrictCode = $districts[0]['code'];
//                 }
//             }
//         }

//         // Lấy danh sách xã/phường của huyện đã chọn
//         $wards = [];
//         if ($selectedDistrictCode) {
//             $responseWards = Http::get("https://provinces.open-api.vn/api/d/{$selectedDistrictCode}?depth=2");
//             if ($responseWards->successful()) {
//                 $wards = $responseWards->json()['wards'] ?? [];
//             }
//         }

//         return view('client.taikhoanUser.edit', [
//             'user' => $user,
//             'profile' => $profile,
//             'provinces' => $provinces,
//             'districts' => $districts,
//             'wards' => $wards,
//             'selectedProvinceCode' => $selectedProvinceCode,
//             'selectedDistrictCode' => $selectedDistrictCode,
//         ]);
//     }

    // Xử lý cập nhật thông tin profile
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'phone'    => 'nullable|string|max:20',
    //         'province' => 'nullable|string|max:100',
    //         'district' => 'nullable|string|max:100',
    //         'ward'     => 'nullable|string|max:100',
    //         'street'   => 'nullable|string|max:255',
    //         'birthday' => 'nullable|date',
    //         'gender'   => 'nullable|in:male,female,other',
    //     ]);

    //     $user = Auth::user();
    //     if (!$user || $user->role !== 'khachhang') {
    //         $user = User::where('role', 'khachhang')->first();
    //     }

    //     if (!$user) {
    //         abort(404, 'Không tìm thấy user khách hàng');
    //     }

    //     $user->name = $request->name;
    //     $user->save();

    //     // Lấy tên tỉnh từ code
    //     $provinceName = null;
    //     if ($request->province) {
    //         $resp = Http::get("https://provinces.open-api.vn/api/p/{$request->province}");
    //         if ($resp->successful()) {
    //             $provinceName = $resp->json()['name'] ?? null;
    //         }
    //     }

    //     // Lấy tên huyện từ code
    //     $districtName = null;
    //     if ($request->district) {
    //         $resp = Http::get("https://provinces.open-api.vn/api/d/{$request->district}");
    //         if ($resp->successful()) {
    //             $districtName = $resp->json()['name'] ?? null;
    //         }
    //     }

    //     // Lấy tên xã/phường từ code
    //     $wardName = null;
    //     if ($request->ward) {
    //         $resp = Http::get("https://provinces.open-api.vn/api/w/{$request->ward}");
    //         if ($resp->successful()) {
    //             $wardName = $resp->json()['name'] ?? null;
    //         }
    //     }

    //     // $profile = $user->profile ?: new UserProfile(['user_id' => $user->user_id]);
    //     $profile->fill([
    //         'phone' => $request->phone,
    //         'province' => $provinceName,
    //         'district' => $districtName,
    //         'ward' => $wardName,
    //         'street' => $request->street,
    //         'birthday' => $request->birthday,
    //         'gender' => $request->gender,
    //     ]);
    //     $profile->save();

    //     return redirect()->route('client.profile.index')->with('success', 'Cập nhật thông tin thành công!');
    // }
// }
