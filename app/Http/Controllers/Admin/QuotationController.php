<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = \App\Models\Quotation::orderBy('created_at', 'desc')->get();
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
