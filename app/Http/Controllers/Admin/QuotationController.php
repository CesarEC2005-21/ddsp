<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function updateStatus(Request $request, \App\Models\Quotation $quotation)
    {
        $request->validate(['estado' => 'required|in:pendiente,completado,cancelado']);
        $quotation->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado actualizado correctamente');
    }
}
