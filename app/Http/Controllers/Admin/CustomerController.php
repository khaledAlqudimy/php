<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'CUSTOMER')->withCount('orders')->latest();

        if ($s = $request->get('q')) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $customers = $query->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'CUSTOMER', 404);

        $user   = $customer->loadCount('orders')->load('addresses');
        $orders = $user->orders()->with('items')->latest()->take(10)->get();
        return view('admin.customers.show', compact('user', 'orders'));
    }
}
