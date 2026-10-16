<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalShopperRequest;
use Illuminate\Http\Request;

class PersonalShopperController extends Controller
{
    public function index(Request $request)
    {
        $query = PersonalShopperRequest::with('user')->latest('created_at');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();
        return view('admin.personal-shopper.index', compact('requests'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:NEW,IN_PROGRESS,FULFILLED,CLOSED'],
        ]);

        PersonalShopperRequest::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث الحالة.');
    }
}
