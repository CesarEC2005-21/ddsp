<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Setting;
use App\Exports\QuotationExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AuditLog;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Quotation::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('apellidos', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_documento', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $quotations = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.quotations.index', compact('quotations'));
    }

    public function show(\App\Models\Quotation $quotation)
    {
        $quotation->load('items.product');
        return response()->json($quotation);
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $request->validate(['estado' => 'required|in:pendiente,completado,cancelado']);
        $quotation->update(['estado' => $request->estado]);

        if ($request->estado === 'cancelado') {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'cancelled_quotation',
                'description' => "Canceló la cotización #{$quotation->id} de {$quotation->nombre}"
            ]);
        }

        return back()->with('success', 'Estado actualizado correctamente');
    }

    public function exportPdf(Quotation $quotation)
    {
        $company = [
            'name' => Setting::get('company_name', 'Sanchez Pharma'),
            'ruc' => Setting::get('company_ruc', ''),
            'address' => Setting::get('company_address', ''),
            'phone' => Setting::get('company_phone', ''),
            'email' => Setting::get('company_email', ''),
        ];

        $pdf = Pdf::loadView('pdf.quotation', compact('quotation', 'company'));
        return $pdf->download('Cotizacion_' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function exportExcel(Quotation $quotation)
    {
        return Excel::download(new QuotationExport($quotation), 'Cotizacion_' . $quotation->id . '.xlsx');
    }
}
