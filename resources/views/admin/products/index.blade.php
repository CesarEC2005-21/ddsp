@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Productos</h2>
        <div style="display: flex; gap: 10px;">
            <button onclick="openModal('importExcelModal')" class="btn" style="background: #10b981; color: white;">
                <i class="fas fa-file-excel"></i> Importar Excel
            </button>
            <button onclick="openModal('newProductModal')" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: #FEE2E2; color: #B91C1C; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ request('codigo') }}" placeholder="Ej. 1187">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Laboratorio</label>
                    <select name="laboratory_id" class="form-control">
                        <option value="">Todos</option>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}" {{ request('laboratory_id') == $lab->id ? 'selected' : '' }}>{{ $lab->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Destacado</label>
                    <select name="is_featured" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->anyFilled(['codigo', 'nombre', 'laboratory_id', 'is_featured', 'estado']))
                        <a href="{{ route('admin.products.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

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
                        <th>Destacado</th>
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
                        <td style="text-align: center;">
                            @if($product->is_featured)
                                <span class="badge" style="background: #FEF3C7; color: #92400E;"><i class="fas fa-star"></i> SÍ</span>
                            @else
                                <span class="badge" style="background: #f1f5f9; color: #64748b;">NO</span>
                            @endif
                        </td>
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
        <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
            {{ $products->appends(request()->query())->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal Nuevo Producto -->
    <div id="newProductModal" class="modal">
        <div class="modal-content" style="max-width: 900px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 20px 30px;">
                <h3 style="color: white; margin: 0;"><i class="fas fa-box-open" style="background: rgba(255,255,255,0.1); color: #10b981;"></i> Registrar Nuevo Producto</h3>
                <span class="close-modal" onclick="closeModal('newProductModal')" style="background: rgba(255,255,255,0.1); color: white;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 0;">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="newProductForm">
                    @csrf
                    <div style="display: flex; min-height: 500px;">
                        <!-- Sidebar de navegación del modal -->
                        <div style="width: 220px; background: #f8fafc; border-right: 1px solid #e2e8f0; padding: 30px 20px; display: flex; flex-direction: column; gap: 10px;">
                            <div style="padding: 10px 15px; border-radius: 8px; background: white; color: var(--primary-color); font-weight: 700; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-info-circle"></i> General
                            </div>
                            <div style="padding: 10px 15px; border-radius: 8px; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-stethoscope"></i> Técnico
                            </div>
                            <div style="padding: 10px 15px; border-radius: 8px; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-image"></i> Multimedia
                            </div>
                        </div>

                        <!-- Contenido del formulario -->
                        <div style="flex: 1; padding: 40px; max-height: 600px; overflow-y: auto;">
                            <!-- Sección: Datos Principales -->
                            <div class="form-section-header" style="margin-bottom: 25px;">
                                <h4 style="margin: 0; font-size: 1.1rem; color: #1e293b; font-weight: 700;">Información Comercial</h4>
                                <p style="margin: 5px 0 0; font-size: 0.85rem; color: #64748b;">Complete los datos básicos del producto para el catálogo.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Código SKU</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-barcode" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                                        <input type="text" name="codigo" class="form-control" style="padding-left: 45px;" placeholder="Ej. PRD-8821" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Nombre Comercial</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Paracetamol 500mg x 100" required>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 10px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Laboratorio</label>
                                    <select name="laboratory_id" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($laboratories as $lab)
                                            <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">U. Medida</label>
                                    <select name="unidad_medida_id" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($unidadMedidas as $um)
                                            <option value="{{ $um->id }}">{{ $um->um }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Precio (S/)</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 15px; top: 10px; color: #64748b; font-weight: 600;">S/</span>
                                        <input type="number" step="0.01" name="precio" class="form-control" style="padding-left: 35px;" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 10px;">
                                <label class="form-label" style="font-weight: 700; color: #334155;">Descripción Corta</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Resumen del producto para listados..."></textarea>
                            </div>

                            <hr style="margin: 40px 0; border: 0; border-top: 1px solid #f1f5f9;">

                            <!-- Sección: Especificaciones Técnicas -->
                            <div class="form-section-header" style="margin-bottom: 25px;">
                                <h4 style="margin: 0; font-size: 1.1rem; color: #1e293b; font-weight: 700;">Especificaciones Médicas</h4>
                                <p style="margin: 5px 0 0; font-size: 0.85rem; color: #64748b;">Información técnica y regulatoria del producto.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Composición / Activos</label>
                                    <textarea name="composicion" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Usos / Indicaciones</label>
                                    <textarea name="usos" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contraindicaciones / Advertencias</label>
                                <textarea name="contraindicaciones" class="form-control" rows="2"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Registro Sanitario</label>
                                    <input type="text" name="registro_sanitario" class="form-control" placeholder="Ej. EE-00412">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Imagen Principal</label>
                                    <input type="file" name="imagen" class="form-control">
                                </div>
                            </div>

                            <div style="background: #f0fdf4; padding: 20px; border-radius: 12px; border: 1px solid #dcfce7; margin-top: 20px;">
                                <label style="display: flex; align-items: center; gap: 15px; cursor: pointer;">
                                    <div style="width: 50px; height: 50px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #16a34a; font-size: 1.5rem; box-shadow: var(--shadow-sm);">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <span style="display: block; font-weight: 700; color: #166534;">Producto Destacado</span>
                                        <span style="display: block; font-size: 0.8rem; color: #14532d;">Aparecerá en la sección principal de la página de inicio.</span>
                                    </div>
                                    <input type="checkbox" name="is_featured" value="1" style="width: 20px; height: 20px; accent-color: #16a34a;">
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 25px 40px; background: white;">
                <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('newProductModal')">Cancelar</button>
                <button type="submit" form="newProductForm" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                    <i class="fas fa-check-circle"></i> Finalizar y Guardar
                </button>
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
                                <label class="form-label">Código del Producto</label>
                                <input type="text" name="codigo" id="edit-codigo" class="form-control" required readonly title="El código no se puede editar.">
                            </div>
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
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="is_featured" id="edit-is_featured" value="1">
                                    <span style="font-weight: 600; color: #1e293b;">Marcar como Producto Destacado (Inicio)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Usos</label>
                            <textarea name="usos" id="edit-usos" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Composición</label>
                            <textarea name="composicion" id="edit-composicion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contraindicaciones</label>
                            <textarea name="contraindicaciones" id="edit-contraindicaciones" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Registro Sanitario</label>
                            <input type="text" name="registro_sanitario" id="edit-registro_sanitario" class="form-control">
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
    <!-- Modal Importar Excel -->
    <div id="importExcelModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-file-excel"></i> Importar Productos</h3>
                <span class="close-modal" onclick="closeModal('importExcelModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Laboratorio</label>
                        <select name="laboratory_id" class="form-control" required>
                            <option value="">Seleccione laboratorio</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label class="form-label">Archivo Excel</label>
                        <input type="file" name="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        <small style="color: #64748b; margin-top: 5px; display: block;">Columnas requeridas: CODIGO, DESCRIPCION, UM, PRECIO</small>
                    </div>
                    <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('importExcelModal')">Cancelar</button>
                        <button type="submit" class="btn" style="background: #10b981; color: white;">Importar Datos</button>
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
        document.getElementById('edit-codigo').value = product.codigo || '';
        document.getElementById('edit-nombre').value = product.nombre;
        document.getElementById('edit-laboratory_id').value = product.laboratory_id;
        document.getElementById('edit-unidad_medida_id').value = product.unidad_medida_id;
        document.getElementById('edit-precio').value = product.precio;
        document.getElementById('edit-descripcion').value = product.descripcion || '';
        document.getElementById('edit-usos').value = product.usos || '';
        document.getElementById('edit-composicion').value = product.composicion || '';
        document.getElementById('edit-contraindicaciones').value = product.contraindicaciones || '';
        document.getElementById('edit-registro_sanitario').value = product.registro_sanitario || '';
        document.getElementById('edit-is_featured').checked = !!product.is_featured;
        openModal('editProductModal');
    }

    function toggleStatus(id) {
        console.log('Toggling status for product:', id);
    }
</script>
@endpush
