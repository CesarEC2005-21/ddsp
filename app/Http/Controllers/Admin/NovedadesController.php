<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Novedad;
use App\Models\Laboratory;

use App\Models\Product;
use App\Models\AuditLog;

class NovedadesController extends Controller
{
    public function index(Request $request)
    {
        $query = Novedad::with(['laboratory', 'product']);

        if ($request->filled('descripcion')) {
            $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
        }
        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('fecha_inicial')) {
            $query->where('fecha_inicial', '>=', $request->fecha_inicial);
        }
        if ($request->filled('fecha_final')) {
            $query->where('fecha_final', '<=', $request->fecha_final);
        }

        $novedades = $query->orderBy('created_at', 'desc')->paginate(10);
        $laboratories = Laboratory::all();
        $products = Product::where('estado', true)->get();
        
        return view('admin.novedades.index', compact('novedades', 'laboratories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'nullable|string|unique:novedades',
            'descripcion' => 'required|string',
            'detalle' => 'nullable|string',
            'fecha_inicial' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicial',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'laboratory_id' => 'nullable|exists:laboratories,id',
            'product_id' => 'nullable|exists:products,id',
            'tipo' => 'required|in:PROMOCION,EVENTO',
        ]);

        $data = $request->except('imagen');
        $data['user_id'] = auth()->id();
        $data['estado'] = $request->has('estado');

        // Auto-generate code if not provided
        if (empty($data['codigo'])) {
            $lastNovedad = Novedad::orderBy('id', 'desc')->first();
            $nextId = $lastNovedad ? $lastNovedad->id + 1 : 1;
            $data['codigo'] = 'NOT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            
            // Double check uniqueness
            while (Novedad::where('codigo', $data['codigo'])->exists()) {
                $nextId++;
                $data['codigo'] = 'NOT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        }

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('novedades', 'public');
        }

        $novedad = Novedad::create($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_promotion',
            'description' => "Creó la promoción/noticia: {$novedad->descripcion} ({$novedad->codigo})"
        ]);

        return redirect()->route('admin.novedades.index')->with('success', 'Novedad creada exitosamente.');
    }

    public function update(Request $request, Novedad $novedad)
    {
        $request->validate([
            'codigo' => 'nullable|string|unique:novedades,codigo,' . $novedad->id,
            'descripcion' => 'required|string',
            'detalle' => 'nullable|string',
            'fecha_inicial' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicial',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'laboratory_id' => 'nullable|exists:laboratories,id',
            'product_id' => 'nullable|exists:products,id',
            'tipo' => 'required|in:PROMOCION,EVENTO',
        ]);

        $data = $request->except('imagen');
        $data['estado'] = $request->has('estado');

        if ($request->hasFile('imagen')) {
            if ($novedad->imagen) {
                \Storage::disk('public')->delete($novedad->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('novedades', 'public');
        }

        $novedad->update($data);

        return redirect()->route('admin.novedades.index')->with('success', 'Novedad actualizada exitosamente.');
    }

    public function destroy(Novedad $novedad)
    {
        if ($novedad->imagen) {
            \Storage::disk('public')->delete($novedad->imagen);
        }
        
        $codigo = $novedad->codigo;
        $descripcion = $novedad->descripcion;

        $novedad->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_promotion',
            'description' => "Eliminó la promoción/noticia: {$descripcion} ({$codigo})"
        ]);

        return redirect()->route('admin.novedades.index')->with('success', 'Novedad eliminada exitosamente.');
    }
}
