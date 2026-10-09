<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $allowed = [
            'site_name', 'site_tagline', 'site_email', 'site_phone',
            'site_address', 'site_whatsapp', 'social_instagram',
            'social_twitter', 'social_facebook', 'social_snapchat',
            'shipping_fee', 'free_shipping_min', 'delivery_days_min',
            'delivery_days_max', 'vat_percent', 'vat_enabled',
            'personal_shopper_enabled', 'auction_enabled',
            'bank_name', 'bank_account_name', 'bank_iban',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->get($key));
            }
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
