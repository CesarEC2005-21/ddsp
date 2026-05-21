<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\RepresentativeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class RepresentativeController extends Controller
{
    public function index()
    {
        $representatives = Representative::with(['zona', 'locations'])->get();
        $zonas = \App\Models\Zona::where('estado', true)->get();
        return view('admin.representatives.index', compact('representatives', 'zonas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'zona_id' => 'required|exists:zonas,id',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'imagen' => 'nullable|image|max:2048',
            'locations' => 'nullable|string' // JSON string
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('representatives', 'public');
        }

        $representative = Representative::create($validated + ['estado' => true]);

        if ($request->filled('locations')) {
            $locations = json_decode($request->locations, true);
            foreach ($locations as $loc) {
                RepresentativeLocation::create([
                    'representative_id' => $representative->id,
                    'zona_id' => $loc['zona_id'],
                    'latitud' => $loc['lat'],
                    'longitud' => $loc['lng'],
                    'descripcion_punto' => $loc['descripcion'] ?? null
                ]);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_representative',
            'description' => "Creó el representante: {$representative->nombre}"
        ]);

        return redirect()->route('admin.representatives.index')->with('success', 'Representante creado exitosamente.');
    }

    public function update(Request $request, Representative $representative)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'zona_id' => 'required|exists:zonas,id',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'imagen' => 'nullable|image|max:2048',
            'locations' => 'nullable|string'
        ]);

        if ($request->hasFile('imagen')) {
            if ($representative->imagen) {
                Storage::disk('public')->delete($representative->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('representatives', 'public');
        }

        $representative->update($validated);

        // Actualizar ubicaciones: eliminamos las anteriores y creamos las nuevas para simplificar
        if ($request->filled('locations')) {
            $representative->locations()->delete();
            $locations = json_decode($request->locations, true);
            foreach ($locations as $loc) {
                RepresentativeLocation::create([
                    'representative_id' => $representative->id,
                    'zona_id' => $loc['zona_id'],
                    'latitud' => $loc['lat'],
                    'longitud' => $loc['lng'],
                    'descripcion_punto' => $loc['descripcion'] ?? null
                ]);
            }
        }

        return redirect()->route('admin.representatives.index')->with('success', 'Representante actualizado correctamente.');
    }

    public function destroy(Representative $representative)
    {
        if ($representative->imagen) {
            Storage::disk('public')->delete($representative->imagen);
        }
        
        $nombre = $representative->nombre;
        
        $representative->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_representative',
            'description' => "Eliminó al ejecutivo: {$nombre}"
        ]);

        return redirect()->route('admin.representatives.index')->with('success', 'Representante eliminado correctamente.');
    }
}
