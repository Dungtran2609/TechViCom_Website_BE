<?php

namespace App\Http\Controllers\Client\Lienhe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lienhe;

class LienHeController extends Controller
{
    public function index()
    {
        return view('client.lienhe.formlienhe');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => ['required', 'regex:/^(0|\+84)[0-9]{9}$/'],
            'message' => 'required|string|min:10',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không đúng định dạng.',
            'phone.required'   => 'Vui lòng nhập số điện thoại.',
            'phone.regex'      => 'Số điện thoại không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            'message.min'      => 'Nội dung liên hệ tối thiểu 10 ký tự.',
        ]);

        Lienhe::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
        ]);redirect()->route('client.lienhe.index')->with('success', 'Gửi liên hệ thành công!');
    }
}
