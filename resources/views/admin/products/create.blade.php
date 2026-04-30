@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.products.index') }}" style="text-decoration: none; color: #555; display: inline-block; margin-bottom: 10px;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        <h2 style="margin: 0; color: #333;">Agregar Nuevo Producto</h2>
    </div>
    <div class="card" style="max-width: 800px;">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Nombre del Producto *</label>
                <input type="text" name="nombre" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; outline: none;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Código Único *</label>
                <input type="text" name="codigo" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; outline: none;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Laboratorio *</label>
                <select name="laboratory_id" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background: white; box-sizing: border-box; outline: none;">
                    <option value="">Seleccione un laboratorio</option>
                    @foreach(\App\Models\Laboratory::all() as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->descripcion }} ({{ $lab->codigo }})</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Precio (S/) *</label>
                    <input type="number" step="0.01" name="precio" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; outline: none;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Unidad (UM) *</label>
                    <input type="text" name="um" placeholder="Ej. CAJA x 100" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; outline: none;">
                </div>
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #444;">Imagen del Producto (Opcional)</label>
                <input type="file" name="imagen" accept="image/*" style="width: 100%; padding: 20px; border: 2px dashed #ccc; border-radius: 6px; box-sizing: border-box; background: #fafafa; cursor: pointer;">
                <small style="color: #888; display: block; margin-top: 5px;">Sube una imagen en formato JPG o PNG. Tamaño máximo recomendado 2MB.</small>
            </div>
            <div style="grid-column: 1 / -1; margin-top: 10px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;"><i class="fas fa-save"></i> Guardar Producto</button>
            </div>
        </form>
    </div>
@endsection
