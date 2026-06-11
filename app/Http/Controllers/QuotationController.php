<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use App\Mail\QuotationMail;
use App\Mail\QuotationAdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\QuotationExport;
use Maatwebsite\Excel\Facades\Excel;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'El carrito está vacío');
        }

        // Sanitizar cantidades de forma defensiva antes de procesar
        foreach ($cart as $id => $details) {
            if (!isset($details['quantity']) || !is_numeric($details['quantity']) || $details['quantity'] < 1) {
                $cart[$id]['quantity'] = 1;
            } else {
                $cart[$id]['quantity'] = (int)$details['quantity'];
            }
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|size:9',
            'tipo_documento' => 'required|in:DNI,RUC',
            'numero_documento' => 'required|string',
            'ciudad' => 'required|string|max:255',
            'direccion_exacta' => 'nullable|string|max:255',
            'latitud' => 'nullable|string|max:50',
            'longitud' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'observaciones' => 'nullable|string',
            'total' => 'required|numeric'
        ]);

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

            // Preparar datos de la empresa
            $company = [
                'name' => Setting::get('company_name', 'Sanchez Pharma'),
                'ruc' => Setting::get('company_ruc', ''),
                'address' => Setting::get('company_address', ''),
                'phone' => Setting::get('company_phone', ''),
                'email' => Setting::get('company_email', ''),
            ];

            $quotation_id = $quotation->id;
            $customer_phone = $quotation->telefono;
            $customer_name = $quotation->nombre;

            app()->terminating(function () use ($quotation, $company) {
                try {
                    // Generar PDF
                    $pdf = Pdf::loadView('pdf.quotation', compact('quotation', 'company'));
                    $pdfContent = $pdf->output();

                    // Enviar Email al cliente
                    try {
                        Mail::to($quotation->email)->send(new QuotationMail($quotation, $pdfContent, $company));
                    } catch (\Exception $e) {
                        \Log::error("Error enviando email al cliente: " . $e->getMessage());
                    }

                    // Generar Excel
                    $excelContent = Excel::raw(new QuotationExport($quotation), \Maatwebsite\Excel\Excel::XLSX);

                    // Enviar Email al administrador
                    try {
                        $adminEmail = env('ADMIN_EMAIL', Setting::get('company_email', 'cesarAEC1234@gmail.com'));
                        Mail::to($adminEmail)->send(new QuotationAdminMail($quotation, $pdfContent, $excelContent, $company));
                    } catch (\Exception $e) {
                        \Log::error("Error enviando email al admin: " . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    \Log::error("Error general en el proceso terminating de cotización #" . $quotation->id . ": " . $e->getMessage());
                }
            });

            session()->forget('cart');

            return redirect()->route('quotation.success')->with([
                'quotation_id' => $quotation_id,
                'customer_phone' => $customer_phone,
                'customer_name' => $customer_name
            ]);

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
