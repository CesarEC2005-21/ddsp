@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Productos</h2>
        <button onclick="openModal('newProductModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </button>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Img</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Laboratorio</th>
                        <th>UM</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->imagen)
                                <img src="{{ asset('storage/' . $product->imagen) }}" alt="" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #eee;">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: 8px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #aaa;"><i class="fas fa-image"></i></div>
                            @endif
                        </td>
                        <td>{{ $product->codigo }}</td>
                        <td style="font-weight: 700; color: #1e293b;">{{ $product->nombre }}</td>
                        <td>{{ $product->laboratory->descripcion ?? 'N/A' }}</td>
                        <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ $product->unidadMedida->um ?? 'N/A' }}</span></td>
                        <td style="font-weight: bold; color: var(--primary-green);">S/ {{ number_format($product->precio, 2) }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $product->estado ? 'checked' : '' }} onchange="toggleStatus({{ $product->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick='openEditModal(@json($product))' class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $product->id }}, '{{ $product->codigo }}', '{{ $product->nombre }}', '{{ route('admin.products.destroy', $product->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 30px; text-align: center; color: #888;">No hay productos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nuevo Producto -->
    <div id="newProductModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3><i class="fas fa-pills"></i> Nuevo Producto</h3>
                <span class="close-modal" onclick="closeModal('newProductModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej. Amoxicilina 500mg" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Laboratorio</label>
                                <select name="laboratory_id" class="form-control" required>
                                    <option value="">Seleccione laboratorio</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Unidad de Medida</label>
                                <select name="unidad_medida_id" class="form-control" required>
                                    <option value="">Seleccione U.M.</option>
                                    @foreach($unidadMedidas as $um)
                                        <option value="{{ $um->id }}">{{ $um->um }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Imagen del Producto</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Información técnica, beneficios, etc."></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; pt-20">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newProductModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Producto -->
    <div id="editProductModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Producto</h3>
                <span class="close-modal" onclick="closeModal('editProductModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Laboratorio</label>
                                <select name="laboratory_id" id="edit-laboratory_id" class="form-control" required>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Unidad de Medida</label>
                                <select name="unidad_medida_id" id="edit-unidad_medida_id" class="form-control" required>
                                    @foreach($unidadMedidas as $um)
                                        <option value="{{ $um->id }}">{{ $um->um }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" name="precio" id="edit-precio" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Actualizar Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" id="edit-descripcion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; pt-20">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editProductModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(product) {
        const form = document.getElementById('editProductForm');
        form.action = `/admin/products/${product.id}`;
        document.getElementById('edit-nombre').value = product.nombre;
        document.getElementById('edit-laboratory_id').value = product.laboratory_id;
        document.getElementById('edit-unidad_medida_id').value = product.unidad_medida_id;
        document.getElementById('edit-precio').value = product.precio;
        document.getElementById('edit-descripcion').value = product.descripcion || '';
        openModal('editProductModal');
    }

    function toggleStatus(id) {
        console.log('Toggling status for product:', id);
    }
</script>
@endpush
