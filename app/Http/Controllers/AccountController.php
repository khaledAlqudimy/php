<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // ══════════════════════════════════════════
    //  الملف الشخصي
    // ══════════════════════════════════════════
    public function profile()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'password'     => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'الاسم مطلوب',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمتا المرور غير متطابقتان',
        ]);

        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    // ══════════════════════════════════════════
    //  طلباتي
    // ══════════════════════════════════════════
    public function orders()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.product', 'address'])
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    // ══════════════════════════════════════════
    //  العناوين
    // ══════════════════════════════════════════
    public function addresses()
    {
        $addresses = auth()->user()->addresses()->latest('created_at')->get();
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'phone'     => ['required', 'string', 'max:20'],
            'country'   => ['required', 'string', 'max:60'],
            'city'      => ['required', 'string', 'max:60'],
            'district'  => ['nullable', 'string', 'max:60'],
            'street'    => ['nullable', 'string', 'max:120'],
            'postal'    => ['nullable', 'string', 'max:20'],
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'phone.required'     => 'رقم الهاتف مطلوب',
            'country.required'   => 'الدولة مطلوبة',
            'city.required'      => 'المدينة مطلوبة',
        ]);

        $isDefault = $request->boolean('is_default');

        // إذا اختار هذا العنوان افتراضياً → أزل الافتراضي القديم
        if ($isDefault) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        // أول عنوان يكون افتراضياً تلقائياً
        if (auth()->user()->addresses()->count() === 0) {
            $isDefault = true;
        }

        auth()->user()->addresses()->create([
            'full_name'  => $request->full_name,
            'phone'      => $request->phone,
            'country'    => $request->country,
            'city'       => $request->city,
            'district'   => $request->district,
            'street'     => $request->street,
            'postal'     => $request->postal,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'تم إضافة العنوان بنجاح.');
    }

    public function deleteAddress(int $id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);

        // لا تحذف العنوان الوحيد المرتبط بطلب
        if ($address->orders()->exists()) {
            return back()->with('error', 'لا يمكن حذف عنوان مرتبط بطلبات سابقة.');
        }

        $address->delete();
        return back()->with('success', 'تم حذف العنوان.');
    }
}
