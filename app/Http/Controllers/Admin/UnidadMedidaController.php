<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        $unidadMedidas = UnidadMedida::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.unidad_medidas.index', compact('unidadMedidas'));
    }

    public function store(Request $request)
    {
        $request->validate(['um' => 'required|string|max:255']);
        UnidadMedida::create($request->all() + ['estado' => true]);
        return redirect()->back()->with('success', 'Unidad de medida creada correctamente');
    }

    public function update(Request $request, UnidadMedida $unidad_medida)
    {
        $request->validate(['um' => 'required|string|max:255']);
        $unidad_medida->update($request->all());
        return redirect()->back()->with('success', 'Unidad de medida actualizada correctamente.');
    }

    public function destroy(UnidadMedida $unidad_medida)
    {
        $unidad_medida->delete();
        return redirect()->back()->with('success', 'Unidad de medida eliminada correctamente.');
    }
}
