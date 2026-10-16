<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest('created_at')->paginate(20);

        // فتح الصفحة = قراءة الرسائل المعروضة
        ContactMessage::whereIn('id', $messages->pluck('id'))->update(['is_read' => true]);

        return view('admin.messages.index', compact('messages'));
    }
}
