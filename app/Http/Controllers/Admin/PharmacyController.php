<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacy::all();
        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        Pharmacy::create($validated + ['estado' => true]);

        return redirect()->route('admin.pharmacies.index')->with('success', 'Botica creada exitosamente.');
    }

    public function update(\Illuminate\Http\Request $request, Pharmacy $pharmacy)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $pharmacy->update($validated);

        return redirect()->route('admin.pharmacies.index')->with('success', 'Botica actualizada correctamente.');
    }

    public function destroy(Pharmacy $pharmacy)
    {
        $pharmacy->delete();
        return redirect()->route('admin.pharmacies.index')->with('success', 'Botica eliminada correctamente.');
    }
}
