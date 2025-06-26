<?php

namespace App\Http\Controllers\Admin\Lienhe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lienhe;

class LienHeAdminController extends Controller
{
    public function index()
    {
        $contacts = Lienhe::latest()->paginate(10); // dùng paginate()
        return view('admin.lienhe.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Lienhe::findOrFail($id);
        $contact->update(['is_read' => true]); // đánh dấu đã đọc
        return view('admin.lienhe.show', compact('contact'));
    }


    public function destroy($id)
    {
        $lienhe = Lienhe::findOrFail($id);
        $lienhe->delete();
        return redirect()->route('admin.lienhe.index')->with('success', 'Đã xoá liên hệ.');
    }
}
