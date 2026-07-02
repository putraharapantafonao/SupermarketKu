<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('grand_total');
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();

        $bestSellingProducts = TransactionDetail::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $salesChart = Transaction::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if (DB::connection()->getDriverName() === 'sqlite') {
            $monthlySalesChart = Transaction::selectRaw("
                strftime('%m', created_at) as month,
                SUM(grand_total) as total
            ")
            ->whereRaw("strftime('%Y', created_at) = ?", [Carbon::now()->format('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        } else {
            $monthlySalesChart = Transaction::selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'totalProducts',
            'totalCustomers',
            'lowStockProducts',
            'bestSellingProducts',
            'salesChart',
            'monthlySalesChart'
        ));
    }

    public function notifications()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();

        $expiredProducts = Product::whereNotNull('expired_date')
            ->whereDate('expired_date', '<=', now())
            ->get();

        $almostExpiredProducts = Product::whereNotNull('expired_date')
            ->whereDate('expired_date', '>', now())
            ->whereDate('expired_date', '<=', now()->addDays(30))
            ->get();

        return view('notifications.index', compact(
            'lowStockProducts',
            'expiredProducts',
            'almostExpiredProducts'
        ));
    }
}
