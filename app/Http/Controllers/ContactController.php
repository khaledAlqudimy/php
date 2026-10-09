<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    // ══════════════════════════════════════════
    //  استقبال رسالة «تواصل معنا»
    // ══════════════════════════════════════════
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'name.required'    => 'الاسم مطلوب',
            'email.required'   => 'البريد الإلكتروني مطلوب',
            'email.email'      => 'صيغة البريد الإلكتروني غير صحيحة',
            'subject.required' => 'الموضوع مطلوب',
            'message.required' => 'الرسالة مطلوبة',
            'message.min'      => 'الرسالة قصيرة جداً',
        ]);

        // الحفظ أولاً حتى لا تضيع الرسالة إذا فشل الإرسال بالبريد
        $msg = ContactMessage::create($data);

        $to = Setting::get('site_email');
        if ($to) {
            try {
                Mail::raw(
                    "رسالة جديدة من نموذج تواصل معنا\n\n"
                    . "الاسم: {$msg->name}\n"
                    . "البريد: {$msg->email}\n"
                    . "الجوال: " . ($msg->phone ?: '—') . "\n"
                    . "الموضوع: {$msg->subject}\n\n"
                    . $msg->message,
                    fn ($m) => $m->to($to)
                        ->replyTo($msg->email, $msg->name)
                        ->subject('تواصل معنا: ' . $msg->subject)
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return back()->with('success', 'تم استلام رسالتك، وسنتواصل معك قريباً.');
    }
}
