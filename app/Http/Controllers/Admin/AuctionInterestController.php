<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionInterest;

class AuctionInterestController extends Controller
{
    public function index()
    {
        $interests = AuctionInterest::with(['auctionItem.product', 'user'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.auction-interest.index', compact('interests'));
    }
}
