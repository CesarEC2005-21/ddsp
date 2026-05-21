<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Laboratory;
// use App\Models\Pharmacy;
use App\Models\Representative;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());

        $stats = [
            'products' => Product::count(),
            'laboratories' => Laboratory::count(),
            'pharmacies' => 0,
            'representatives' => Representative::count(),
            'quotations' => Quotation::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->count(),
            'total_quoted' => Quotation::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->sum('total')
        ];

        // Data for chart: Quotations by day
        $quotationsChart = Quotation::select(
            DB::raw('COUNT(id) as count'),
            DB::raw("DATE_FORMAT(created_at, '%d/%m') as label")
        )
        ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
        ->groupBy('label')
        ->orderBy(DB::raw("MIN(created_at)"), 'asc')
        ->get();

        // Data for chart: Products by Laboratory (Filtered by date)
        $labsChart = Laboratory::withCount(['products' => function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        }])
        ->orderBy('products_count', 'desc')
        ->limit(5)
        ->get();

        return view('admin.dashboard', compact('stats', 'quotationsChart', 'labsChart', 'dateFrom', 'dateTo'));
    }
}
