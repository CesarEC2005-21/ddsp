<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['laboratory', 'unidadMedida']);

        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('laboratory_id')) {
            $query->where('laboratory_id', $request->laboratory_id);
        }
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured == '1');
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado == '1');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $laboratories = \App\Models\Laboratory::all();
        $unidadMedidas = \App\Models\UnidadMedida::where('estado', true)->get();
        
        return view('admin.products.index', compact('products', 'laboratories', 'unidadMedidas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:products,codigo',
            'nombre' => 'required|string|max:255',
            'laboratory_id' => 'required|exists:laboratories,id',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'usos' => 'nullable|string',
            'composicion' => 'nullable|string',
            'contraindicaciones' => 'nullable|string',
            'registro_sanitario' => 'nullable|string',
            'is_featured' => 'nullable',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('products', 'public');
        }

        $product = Product::create(array_merge($validated, [
            'is_featured' => $request->has('is_featured'),
            'imagen' => $path,
            'estado' => true,
            'usuario_origen' => auth()->id(),
            'usuario_actualizo' => auth()->id(),
        ]));

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_product',
            'description' => "Ingresó el producto: {$product->nombre} ({$product->codigo})"
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'laboratory_id' => 'required|exists:laboratories,id',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'usos' => 'nullable|string',
            'composicion' => 'nullable|string',
            'contraindicaciones' => 'nullable|string',
            'registro_sanitario' => 'nullable|string',
            'is_featured' => 'nullable',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('products', 'public');
        } else {
            unset($validated['imagen']);
        }

        $product->update(array_merge($validated, [
            'is_featured' => $request->has('is_featured'),
            'usuario_actualizo' => auth()->id(),
        ]));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        try {
            $imagePath = $product->imagen;
            $nombre = $product->nombre;
            $codigo = $product->codigo;
            
            $product->delete();
            
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_product',
                'description' => "Eliminó el producto: {$nombre} ({$codigo})"
            ]);
            
            return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->back()->withErrors(['error' => 'No se puede eliminar el producto porque ya está incluido en pedidos o cotizaciones. Te sugerimos desactivar su "Estado" en la lista para ocultarlo del catálogo sin afectar el historial.']);
            }
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error en la base de datos al intentar eliminar el producto.']);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'laboratory_id' => 'required|exists:laboratories,id',
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\ProductsImport($request->laboratory_id), 
                $request->file('file')
            );
            return redirect()->route('admin.products.index')->with('success', 'Productos importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Error al importar: ' . $e->getMessage()]);
        }
    }
}
