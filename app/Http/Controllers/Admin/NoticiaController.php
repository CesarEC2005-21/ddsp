<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Noticia;
use App\Models\Laboratory;

use App\Models\Product;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::with(['laboratory', 'product']);

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

        $noticias = $query->orderBy('created_at', 'desc')->paginate(10);
        $laboratories = Laboratory::all();
        $products = Product::where('estado', true)->get();
        
        return view('admin.noticias.index', compact('noticias', 'laboratories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'nullable|string|unique:noticias',
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
            $lastNoticia = Noticia::orderBy('id', 'desc')->first();
            $nextId = $lastNoticia ? $lastNoticia->id + 1 : 1;
            $data['codigo'] = 'NOT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            
            // Double check uniqueness
            while (Noticia::where('codigo', $data['codigo'])->exists()) {
                $nextId++;
                $data['codigo'] = 'NOT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        }

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        Noticia::create($data);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia creada exitosamente.');
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'codigo' => 'nullable|string|unique:noticias,codigo,' . $noticia->id,
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
            if ($noticia->imagen) {
                \Storage::disk('public')->delete($noticia->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        $noticia->update($data);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia actualizada exitosamente.');
    }

    public function destroy(Noticia $noticia)
    {
        if ($noticia->imagen) {
            \Storage::disk('public')->delete($noticia->imagen);
        }
        $noticia->delete();
        return redirect()->route('admin.noticias.index')->with('success', 'Noticia eliminada exitosamente.');
    }
}
