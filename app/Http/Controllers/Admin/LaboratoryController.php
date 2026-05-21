<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Laboratory::query();

        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('descripcion')) {
            $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
        }
        if ($request->filled('is_top')) {
            $query->where('is_top', $request->is_top == '1');
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado == '1');
        }

        $laboratories = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.laboratories.index', compact('laboratories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255|unique:laboratories,descripcion',
            'logo' => 'nullable|image|max:2048',
        ], [
            'descripcion.unique' => 'Este laboratorio ya se encuentra registrado.'
        ]);

        $lastId = Laboratory::max('id') ?? 0;
        $autoCode = 'LAB-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('laboratories', 'public');
        }

        $laboratory = Laboratory::create([
            'codigo' => $autoCode,
            'descripcion' => $request->descripcion,
            'logo' => $logoPath,
            'is_top' => false,
            'estado' => true,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_laboratory',
            'description' => "Creó el laboratorio: {$laboratory->descripcion} ({$laboratory->codigo})"
        ]);

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratorio creado correctamente con código ' . $autoCode);
    }

    public function toggleTop(Laboratory $laboratory)
    {
        $laboratory->update(['is_top' => !$laboratory->is_top]);
        return back()->with('success', 'Estado destacado actualizado correctamente.');
    }

    public function update(Request $request, Laboratory $laboratory)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255|unique:laboratories,descripcion,' . $laboratory->id,
            'logo' => 'nullable|image|max:2048',
        ], [
            'descripcion.unique' => 'Este laboratorio ya se encuentra registrado.'
        ]);

        if ($request->hasFile('logo')) {
            if ($laboratory->logo) {
                Storage::disk('public')->delete($laboratory->logo);
            }
            $laboratory->logo = $request->file('logo')->store('laboratories', 'public');
        }

        $laboratory->descripcion = $request->descripcion;
        $laboratory->save();

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratorio actualizado correctamente.');
    }

    public function destroy(Laboratory $laboratory)
    {
        if ($laboratory->logo) {
            Storage::disk('public')->delete($laboratory->logo);
        }
        
        $codigo = $laboratory->codigo;
        $descripcion = $laboratory->descripcion;

        $laboratory->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_laboratory',
            'description' => "Eliminó el laboratorio: {$descripcion} ({$codigo})"
        ]);

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratorio eliminado correctamente.');
    }
}
