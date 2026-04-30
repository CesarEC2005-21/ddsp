<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['laboratory', 'unidadMedida'])->get();
        $laboratories = \App\Models\Laboratory::all();
        $unidadMedidas = \App\Models\UnidadMedida::where('estado', true)->get();
        return view('admin.products.index', compact('products', 'laboratories', 'unidadMedidas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'laboratory_id' => 'required|exists:laboratories,id',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $lastId = Product::max('id') ?? 0;
        $autoCode = 'PRD-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('products', 'public');
        }

        Product::create([
            'nombre' => $validated['nombre'],
            'codigo' => $autoCode,
            'laboratory_id' => $validated['laboratory_id'],
            'unidad_medida_id' => $validated['unidad_medida_id'],
            'precio' => $validated['precio'],
            'descripcion' => $validated['descripcion'],
            'imagen' => $path,
            'estado' => true,
            'usuario_origen' => auth()->id(),
            'usuario_actualizo' => auth()->id(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente con código ' . $autoCode);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'laboratory_id' => 'required|exists:laboratories,id',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $product->imagen = $request->file('imagen')->store('products', 'public');
        }

        $product->update([
            'nombre' => $validated['nombre'],
            'laboratory_id' => $validated['laboratory_id'],
            'unidad_medida_id' => $validated['unidad_medida_id'],
            'precio' => $validated['precio'],
            'descripcion' => $validated['descripcion'],
            'usuario_actualizo' => auth()->id(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->imagen) {
            Storage::disk('public')->delete($product->imagen);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
