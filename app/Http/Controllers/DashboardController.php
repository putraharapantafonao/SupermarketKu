<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Transaction::whereDate('created_at', today())->sum('grand_total');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $lowStockProducts = Product::lowStock()->get();
        $bestSellingProducts = TransactionDetail::with('product')->bestSellers()->get();
        $salesChart = Transaction::salesByDay()->get();
        $monthlySalesChart = Transaction::salesByMonth()->get();

        return view('dashboard', compact(
            'todaySales', 'todayTransactions',
            'totalProducts', 'totalCustomers',
            'lowStockProducts', 'bestSellingProducts',
            'salesChart', 'monthlySalesChart',
        ));
    }

    public function notifications()
    {
        return view('notifications.index', [
            'lowStockProducts' => Product::lowStock()->get(),
            'expiredProducts' => Product::expired()->get(),
            'almostExpiredProducts' => Product::almostExpired()->get(),
        ]);
    }
}
