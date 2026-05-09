<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quotation;
use App\Models\Product;
use App\Models\QuotationItem;
use App\Models\ProductPriceHistory;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Clientes únicos (por email)
        $totalCustomers = Quotation::distinct('email')->count('email');

        // 2. Cotizaciones por Estado
        $quotationsPending   = Quotation::where('estado', 'pendiente')->count();
        $quotationsCompleted = Quotation::where('estado', 'completado')->count();
        $quotationsCancelled = Quotation::where('estado', 'cancelado')->count();
        $totalQuotations     = Quotation::count();

        // 3. Historial de Precios
        $priceHistoryCount = ProductPriceHistory::count();

        // 4. Productos más buscados/solicitados (por cantidad en cotizaciones)
        $topProducts = QuotationItem::select('product_id', DB::raw('SUM(cantidad) as total_solicitado'))
            ->groupBy('product_id')
            ->orderByDesc('total_solicitado')
            ->with('product.laboratory')
            ->limit(6)
            ->get();

        // 5. Ingresos (cotizaciones completadas)
        $revenue = Quotation::where('estado', 'completado')->sum('total');

        // 6. Laboratorios más mencionados
        $topLaboratories = DB::table('quotation_items')
            ->join('products', 'quotation_items.product_id', '=', 'products.id')
            ->join('laboratories', 'products.laboratory_id', '=', 'laboratories.id')
            ->select('laboratories.descripcion as lab_name', DB::raw('COUNT(*) as menciones'))
            ->groupBy('laboratories.id', 'laboratories.descripcion')
            ->orderByDesc('menciones')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalCustomers', 'totalQuotations', 'quotationsPending',
            'quotationsCompleted', 'quotationsCancelled', 'priceHistoryCount',
            'topProducts', 'revenue', 'topLaboratories'
        ));
    }

    public function customers(Request $request)
    {
        $sort   = $request->get('sort', 'total_pedidos');
        $search = $request->get('search');

        $allowed = ['total_pedidos', 'total_gastado', 'email'];
        $sort = in_array($sort, $allowed) ? $sort : 'total_pedidos';

        $query = Quotation::select(
                'email',
                DB::raw('MAX(nombre) as nombre'),
                DB::raw('MAX(apellidos) as apellidos'),
                DB::raw('MAX(numero_documento) as numero_documento'),
                DB::raw('MAX(tipo_documento) as tipo_documento'),
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(total) as total_gastado')
            )
            ->groupBy('email');

        if ($search) {
            $query->having(DB::raw('MAX(nombre)'), 'like', "%{$search}%")
                  ->orHaving(DB::raw('MAX(apellidos)'), 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $customers = $query->orderByDesc($sort)->paginate(15);

        return view('admin.reports.customers', compact('customers'));
    }

    public function quotations(Request $request)
    {
        $status    = $request->get('status');
        $search    = $request->get('search');
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');

        $query = Quotation::query();

        if ($status) {
            $query->where('estado', $status);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $quotations = $query->latest()->paginate(20);

        return view('admin.reports.quotations', compact('quotations', 'status'));
    }

    public function products(Request $request)
    {
        $search   = $request->get('search');
        $tipo     = $request->get('tipo');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $query = ProductPriceHistory::with('product.laboratory')->latest();

        if ($search) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }
        if ($tipo === 'up') {
            $query->whereRaw('new_price > old_price');
        } elseif ($tipo === 'down') {
            $query->whereRaw('new_price < old_price');
        }
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $history = $query->paginate(20);

        return view('admin.reports.products', compact('history'));
    }
}
