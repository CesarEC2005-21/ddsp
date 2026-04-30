<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'El carrito está vacío');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|size:9',
            'tipo_documento' => 'required|in:DNI,RUC',
            'numero_documento' => 'required|string',
            'ciudad' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'observaciones' => 'nullable|string',
            'total' => 'required|numeric'
        ]);

        // Validación adicional para longitud de documento
        if ($validated['tipo_documento'] == 'DNI' && strlen($validated['numero_documento']) != 8) {
            return back()->withErrors(['numero_documento' => 'El DNI debe tener 8 dígitos'])->withInput();
        }
        if ($validated['tipo_documento'] == 'RUC' && strlen($validated['numero_documento']) != 11) {
            return back()->withErrors(['numero_documento' => 'El RUC debe tener 11 dígitos'])->withInput();
        }

        try {
            DB::beginTransaction();

            $quotation = Quotation::create($validated + ['estado' => 'pendiente']);

            foreach ($cart as $id => $details) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $id,
                    'cantidad' => $details['quantity'],
                    'precio_unitario' => $details['price']
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('quotation.success')->with('quotation_id', $quotation->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar su solicitud: ' . $e->getMessage())->withInput();
        }
    }

    public function success()
    {
        return view('landing.quotation_success');
    }
}
