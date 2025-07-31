<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsAdminController extends Controller
{

    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('message', 'like', '%' . $keyword . '%');
            });
        }

        $contacts = $query->latest()->paginate(10);

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
