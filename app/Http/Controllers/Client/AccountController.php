<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}