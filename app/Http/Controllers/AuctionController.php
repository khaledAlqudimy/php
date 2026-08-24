<?php

namespace App\Http\Controllers;

use App\Models\AuctionItem;
use App\Models\AuctionInterest;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    // ══════════════════════════════════════════
    //  صفحة المزادات
    // ══════════════════════════════════════════
    public function index()
    {
        // المزادات النشطة التي لم ينته موعدها
        $activeAuctions = AuctionItem::where('is_active', true)
            ->where('deadline', '>', now())
            ->with(['product.images', 'product.category'])
            ->withCount('interests')
            ->orderBy('deadline')
            ->get();

        // المزادات المنتهية (آخر 5)
        $closedAuctions = AuctionItem::where(function ($q) {
                $q->where('is_active', false)
                  ->orWhere('deadline', '<=', now());
            })
            ->with(['product.images'])
            ->withCount('interests')
            ->orderByDesc('deadline')
            ->take(5)
            ->get();

        return view('auction.index', compact('activeAuctions', 'closedAuctions'));
    }

    // ══════════════════════════════════════════
    //  تسجيل الاهتمام بمزاد
    // ══════════════════════════════════════════
    public function storeInterest(Request $request)
    {
        $request->validate([
            'auction_item_id' => ['required', 'exists:auction_items,id'],
            'name'            => ['required', 'string', 'max:100'],
            'phone'           => ['required', 'string', 'max:20'],
            'email'           => ['required', 'email', 'max:100'],
            'max_budget'      => ['required', 'numeric', 'min:1'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ], [
            'auction_item_id.required' => 'اختر المزاد',
            'name.required'            => 'الاسم مطلوب',
            'phone.required'           => 'رقم الهاتف مطلوب',
            'email.required'           => 'البريد الإلكتروني مطلوب',
            'email.email'              => 'صيغة البريد الإلكتروني غير صحيحة',
            'max_budget.required'      => 'الميزانية القصوى مطلوبة',
            'max_budget.min'           => 'يجب أن تكون الميزانية أكبر من صفر',
        ]);

        // التحقق أن المزاد لا يزال مفتوحاً
        $auction = AuctionItem::where('id', $request->auction_item_id)
            ->where('is_active', true)
            ->where('deadline', '>', now())
            ->firstOrFail();

        AuctionInterest::create([
            'auction_item_id' => $auction->id,
            'user_id'         => auth()->id(),
            'name'            => $request->name,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'max_budget'      => $request->max_budget,
            'notes'           => $request->notes,
        ]);

        return redirect()->route('auction.index')
            ->with('success', 'تم تسجيل اهتمامك بنجاح! سنتواصل معك قبل موعد المزاد.');
    }
}
