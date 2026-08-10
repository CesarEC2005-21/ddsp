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
        $laboratories = \App\Models\Laboratory::orderBy('descripcion', 'asc')->get();
        $unidadMedidas = \App\Models\UnidadMedida::where('estado', true)->get();
        
        return view('admin.products.index', compact('products', 'laboratories', 'unidadMedidas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'nullable|string|max:50|unique:products,codigo',
            'nombre' => 'required|string|max:255|unique:products,nombre',
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
        ], [
            'nombre.unique' => 'Este producto ya se encuentra registrado.',
            'codigo.unique' => 'El código de producto ya existe.'
        ]);

        if (empty($validated['codigo'])) {
            $lastId = Product::max('id') ?? 0;
            $validated['codigo'] = 'PRD-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
            // Ensure uniqueness in case of gaps
            while (Product::where('codigo', $validated['codigo'])->exists()) {
                $lastId++;
                $validated['codigo'] = 'PRD-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
            }
        }

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
            'nombre' => 'required|string|max:255|unique:products,nombre,' . $product->id,
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
        ], [
            'nombre.unique' => 'Este producto ya se encuentra registrado.'
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('products', 'public');
        } else {
            unset($validated['imagen']);
        }

        // Record price history if changed (rounded to 2 decimal places to avoid float precision issues)
        $oldPrice = round(floatval($product->precio), 2);
        $newPrice = round(floatval($validated['precio']), 2);
        if ($oldPrice != $newPrice) {
            \App\Models\ProductPriceHistory::create([
                'product_id' => $product->id,
                'precio' => $oldPrice,
                'precio_nuevo' => $newPrice,
                'user_id' => auth()->id()
            ]);
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
            'laboratory_id' => 'nullable|exists:laboratories,id',
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            set_time_limit(0); // Prevents timeout during large imports
            $import = new \App\Imports\ProductsImport($request->laboratory_id);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $results = $import->results;
            
            $msg = 'Importación completada.';
            if (count($results['new_products']) > 0 || count($results['updated_products']) > 0 || count($results['new_laboratories']) > 0) {
                return redirect()->route('admin.products.index')->with([
                    'success' => $msg,
                    'import_results' => $results
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Productos importados correctamente (Sin cambios nuevos).');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Error al importar: ' . $e->getMessage()]);
        }
    }

    public function importGeneral(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:50000',
        ]);

        try {
            set_time_limit(0); // Prevents timeout during large imports
            $import = new \App\Imports\GeneralProductsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $results = $import->results;

            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'general_import',
                'description' => "Realizó una importación general de productos desde Excel.",
                'details' => json_encode($results)
            ]);
            
            $msg = 'Importación general completada. Los productos, laboratorios y unidades de medida han sido actualizados/creados.';
            if (count($results['new_products']) > 0 || count($results['updated_products']) > 0 || count($results['new_laboratories']) > 0) {
                return redirect()->route('admin.products.index')->with([
                    'success' => $msg,
                    'import_results' => $results
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', $msg . ' (Sin cambios nuevos detectados).');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Error al importar reporte general: ' . $e->getMessage()]);
        }
    }
    public function priceHistory(Product $product)
    {
        $history = $product->priceHistory()->with('user')->get();
        return response()->json($history);
    }

    public function deleteByLab(\App\Models\Laboratory $laboratory)
    {
        $count = Product::where('laboratory_id', $laboratory->id)->count();
        
        if ($count === 0) {
            return redirect()->back()->with('info', 'No hay productos para eliminar en este laboratorio.');
        }

        // Delete images
        $products = Product::where('laboratory_id', $laboratory->id)->get();
        foreach ($products as $product) {
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
        }

        Product::where('laboratory_id', $laboratory->id)->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'mass_deleted_products',
            'description' => "Eliminó TODOS los productos ({$count}) del laboratorio: {$laboratory->descripcion}"
        ]);

        return redirect()->route('admin.products.index')->with('success', "Se han eliminado correctamente {$count} productos del laboratorio {$laboratory->descripcion}.");
    }
}
