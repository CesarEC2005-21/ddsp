<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificado::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        $certificados = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.certificados.index', compact('certificados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'imagen'      => 'nullable|image|max:2048',
            'activo'      => 'nullable|boolean',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('certificados', 'public');
        }

        Certificado::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen'      => $imagenPath,
            'activo'      => $request->has('activo') ? true : false,
        ]);

        return redirect()->route('admin.certificados.index')
            ->with('success', 'Certificado creado correctamente.');
    }

    public function update(Request $request, Certificado $certificado)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'imagen'      => 'nullable|image|max:2048',
            'activo'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($certificado->imagen) {
                Storage::disk('public')->delete($certificado->imagen);
            }
            $certificado->imagen = $request->file('imagen')->store('certificados', 'public');
        }

        $certificado->nombre      = $request->nombre;
        $certificado->descripcion = $request->descripcion;
        $certificado->activo      = $request->has('activo') ? true : false;
        $certificado->save();

        return redirect()->route('admin.certificados.index')
            ->with('success', 'Certificado actualizado correctamente.');
    }

    public function destroy(Certificado $certificado)
    {
        if ($certificado->imagen) {
            Storage::disk('public')->delete($certificado->imagen);
        }
        $certificado->delete();

        return redirect()->route('admin.certificados.index')
            ->with('success', 'Certificado eliminado correctamente.');
    }
}
