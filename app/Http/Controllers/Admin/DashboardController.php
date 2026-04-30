<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use App\Models\Representative;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'laboratories' => Laboratory::count(),
            'pharmacies' => Pharmacy::count(),
            'representatives' => Representative::count(),
            'quotations' => Quotation::count(),
            'total_quoted' => Quotation::sum('total')
        ];

        // Data for chart: Quotations by month (last 6 months)
        $quotationsChart = Quotation::select(
            DB::raw('COUNT(id) as count'),
            DB::raw("DATE_FORMAT(created_at, '%M') as month")
        )
        ->groupBy('month')
        ->orderBy('created_at', 'asc')
        ->limit(6)
        ->get();

        // Data for chart: Products by Laboratory
        $labsChart = Laboratory::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'quotationsChart', 'labsChart'));
    }
}
