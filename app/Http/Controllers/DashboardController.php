<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\ServiceJob;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');
        $totalProducts = Product::sum('stock');
        $totalCustomers = Customer::count();
        $pendingJobs = ServiceJob::where('status', 'pending')->count();
        
        $lowStockProducts = Product::whereColumn('stock', '<=', 'reorder_level')
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();
        
        $recentSales = Sale::with(['customer', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'totalProducts',
            'totalCustomers',
            'pendingJobs',
            'lowStockProducts',
            'recentSales'
        ));
    }
}