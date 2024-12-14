<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPurchase;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalPurchase = VendorPurchase::count();
        $totalVendor = Vendor::count();

        return view('home', compact('totalOrders', 'totalUsers', 'totalPurchase', 'totalVendor'));
    }
}
