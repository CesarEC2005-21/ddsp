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

        // 3. Historial de Precios (solo cambios reales)
        $priceHistoryCount = ProductPriceHistory::whereHas('product', function($q) {
            $q->where(function($sub) {
                // Registros nuevos con precio_nuevo diferente al precio anterior guardado
                $sub->whereNotNull('product_price_histories.precio_nuevo')
                    ->whereColumn('product_price_histories.precio_nuevo', '!=', 'product_price_histories.precio');
            });
        })->orWhere(function($q) {
            // Registros antiguos donde el precio actual del producto es diferente al guardado
            $q->whereNull('product_price_histories.precio_nuevo')
              ->whereHas('product', function($sub) {
                  $sub->whereColumn('products.precio', '!=', 'product_price_histories.precio');
              });
        })->count();

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

        $totalFiltrado = (clone $query)->sum('total');
        $quotations = $query->latest()->paginate(20);

        return view('admin.reports.quotations', compact('quotations', 'status', 'totalFiltrado'));
    }

    public function products(Request $request)
    {
        $search   = $request->get('search');
        $tipo     = $request->get('tipo');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $query = ProductPriceHistory::with('product.laboratory')->latest();

        // Filtrar solo cambios reales de precio
        $query->where(function($q) {
            $q->where(function($sub) {
                $sub->whereNotNull('product_price_histories.precio_nuevo')
                    ->whereColumn('product_price_histories.precio_nuevo', '!=', 'product_price_histories.precio');
            })->orWhere(function($sub) {
                $sub->whereNull('product_price_histories.precio_nuevo')
                    ->whereHas('product', function($q2) {
                        $q2->whereColumn('products.precio', '!=', 'product_price_histories.precio');
                    });
            });
        });

        if ($search) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        if ($tipo === 'up') {
            $query->where(function($q) {
                $q->where(function($sub) {
                    $sub->whereNotNull('product_price_histories.precio_nuevo')
                        ->whereColumn('product_price_histories.precio_nuevo', '>', 'product_price_histories.precio');
                })->orWhere(function($sub) {
                    $sub->whereNull('product_price_histories.precio_nuevo')
                        ->whereHas('product', function($q2) {
                            $q2->whereColumn('products.precio', '>', 'product_price_histories.precio');
                        });
                });
            });
        } elseif ($tipo === 'down') {
            $query->where(function($q) {
                $q->where(function($sub) {
                    $sub->whereNotNull('product_price_histories.precio_nuevo')
                        ->whereColumn('product_price_histories.precio_nuevo', '<', 'product_price_histories.precio');
                })->orWhere(function($sub) {
                    $sub->whereNull('product_price_histories.precio_nuevo')
                        ->whereHas('product', function($q2) {
                            $q2->whereColumn('products.precio', '<', 'product_price_histories.precio');
                        });
                });
            });
        }
        if ($dateFrom) {
            $query->whereDate('product_price_histories.created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('product_price_histories.created_at', '<=', $dateTo);
        }

        $history = $query->paginate(20);

        // Assign old_price and new_price for the view
        $history->getCollection()->transform(function($h) {
            $h->old_price = $h->precio;
            if ($h->precio_nuevo) {
                $h->new_price = $h->precio_nuevo;
            } else {
                $h->new_price = $h->product ? round(floatval($h->product->precio), 2) : $h->precio;
            }
            return $h;
        });

        return view('admin.reports.products', compact('history'));
    }
}
