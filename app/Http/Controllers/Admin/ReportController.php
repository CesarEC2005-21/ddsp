<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quotation;
use App\Models\Product;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Resumen General
        $totalQuotations = Quotation::count();
        $totalRevenue = Quotation::where('estado', 'aprobado')->sum('total');
        $pendingQuotations = Quotation::where('estado', 'pendiente')->count();

        // Ventas por Estado
        $quotationsByStatus = Quotation::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Top 5 Productos más solicitados
        $topProducts = QuotationItem::select('product_id', DB::raw('sum(cantidad) as total_vendido'))
            ->join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->where('quotations.estado', 'aprobado')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // Laboratorios más populares (basado en cantidad de productos en cotizaciones aprobadas)
        $topLaboratories = DB::table('quotation_items')
            ->join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->join('products', 'quotation_items.product_id', '=', 'products.id')
            ->join('laboratories', 'products.laboratory_id', '=', 'laboratories.id')
            ->where('quotations.estado', 'aprobado')
            ->select('laboratories.descripcion as lab_name', DB::raw('sum(quotation_items.cantidad) as total_vendido'))
            ->groupBy('laboratories.id', 'laboratories.descripcion')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // Cotizaciones por Mes (últimos 6 meses)
        $monthlyQuotations = Quotation::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.reports.index', compact(
            'totalQuotations', 'totalRevenue', 'pendingQuotations',
            'quotationsByStatus', 'topProducts', 'topLaboratories', 'monthlyQuotations'
        ));
    }
}
