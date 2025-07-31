<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('client.account.edit', compact('user'));
    }

    public function show()
    {
        $user = auth()->user();
        return view('client.account.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'image_profile' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;

        // Xử lý upload ảnh đại diện nếu có
        if ($request->hasFile('image_profile')) {
            $file = $request->file('image_profile');
            $path = $file->store('avatars', 'public');
            $user->image_profile = $path;
        }

        $user->save();

        return redirect()->route('account.edit')->with('success', 'Cập nhật thông tin thành công!');
    }

    // API Methods for React Frontend
    public function apiProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function apiUpdateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công',
            'data' => $user
        ]);
    }

    public function apiUpdatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mật khẩu thành công'
        ]);
    }
}