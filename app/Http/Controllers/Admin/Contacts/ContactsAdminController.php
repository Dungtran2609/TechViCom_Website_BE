<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsAdminController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10); // dùng paginate()
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]); // đánh dấu đã đọc
        return view('admin.contacts.show', compact('contact'));
    }


    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin-control.contacts.index')->with('success', 'Đã xoá liên hệ.');
    }
}
