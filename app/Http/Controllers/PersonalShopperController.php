<?php

namespace App\Http\Controllers;

use App\Models\PersonalShopperRequest;
use Illuminate\Http\Request;

class PersonalShopperController extends Controller
{
    // ══════════════════════════════════════════
    //  صفحة الباحث الشخصي
    // ══════════════════════════════════════════
    public function index()
    {
        // تعبئة بيانات المستخدم المسجّل تلقائياً في النموذج
        $user = auth()->user();
        return view('personal-shopper.index', compact('user'));
    }

    // ══════════════════════════════════════════
    //  حفظ الطلب
    // ══════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'item_type'         => ['required', 'string', 'max:100'],
            'era'               => ['nullable', 'string', 'max:80'],
            'country_of_origin' => ['nullable', 'string', 'max:60'],
            'budget'            => ['nullable', 'numeric', 'min:0'],
            'description'       => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'item_type.required'   => 'نوع القطعة مطلوب',
            'description.required' => 'وصف القطعة مطلوب',
            'description.min'      => 'يرجى كتابة وصف أكثر تفصيلاً (20 حرف على الأقل)',
        ]);

        PersonalShopperRequest::create([
            'user_id'           => auth()->id(),
            'item_type'         => $request->item_type,
            'era'               => $request->era,
            'country_of_origin' => $request->country_of_origin,
            'budget'            => $request->budget,
            'description'       => $request->description,
            'status'            => 'NEW',
        ]);

        return redirect()->route('personal-shopper.index')
            ->with('success', 'تم استلام طلبك! سيتواصل معك فريق الباحثين خلال 48 ساعة.');
    }
}
