<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index()
    {
        $zonas = Zona::all();
        return view('admin.zonas.index', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre_zona' => 'required|string|max:255']);
        Zona::create($request->all() + ['estado' => true]);
        return redirect()->back()->with('success', 'Zona creada correctamente');
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate(['nombre_zona' => 'required|string|max:255']);
        $zona->update($request->all());
        return redirect()->back()->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy(Zona $zona)
    {
        $zona->delete();
        return redirect()->back()->with('success', 'Zona eliminada correctamente.');
    }
}
