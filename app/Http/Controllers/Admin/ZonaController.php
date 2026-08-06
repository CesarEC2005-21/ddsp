<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index(Request $request)
    {
        $query = Zona::query();

        if ($request->filled('nombre')) {
            $query->where('nombre_zona', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $zonas = $query->paginate(10);
        return view('admin.zonas.index', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_zona' => 'required|string|max:255|unique:zonas,nombre_zona'
        ], [
            'nombre_zona.unique' => 'Esta zona ya se encuentra registrada.'
        ]);
        
        Zona::create($request->all() + ['estado' => true]);
        return redirect()->back()->with('success', 'Zona creada correctamente');
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nombre_zona' => 'required|string|max:255|unique:zonas,nombre_zona,' . $zona->id
        ], [
            'nombre_zona.unique' => 'Esta zona ya se encuentra registrada.'
        ]);

        $zona->update($request->all());
        return redirect()->back()->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy(Zona $zona)
    {
        $zona->delete();
        return redirect()->back()->with('success', 'Zona eliminada correctamente.');
    }
}
