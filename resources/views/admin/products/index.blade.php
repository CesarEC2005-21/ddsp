@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Productos</h2>
        <div style="display: flex; gap: 10px;">
            <button onclick="openModal('importExcelModal')" class="btn" style="background: #10b981; color: white;"><i class="fas fa-file-import"></i> Importar Excel</button>
            <button onclick="openModal('newProductModal')" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Producto</button>
        </div>
    </div>

    @if(session('import_results'))
    <div class="card" style="margin-bottom: 25px; border-left: 5px solid #10b981; background: #f0fdf4;">
        <div style="padding: 20px;">
            <h4 style="margin: 0 0 15px; color: #166534; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clipboard-check"></i> Resumen de Importación
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                @if(count(session('import_results')['new_laboratories']) > 0)
                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #dcfce7;">
                    <span style="display: block; font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Lab. Creados</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #16a34a;">{{ count(session('import_results')['new_laboratories']) }}</span>
                    <details style="margin-top: 5px; font-size: 0.85rem; color: #4b5563;">
                        <summary style="cursor: pointer; color: #16a34a;">Ver detalles</summary>
                        <ul style="margin: 5px 0 0; padding-left: 15px;">
                            @foreach(session('import_results')['new_laboratories'] as $lab)
                                <li>{{ $lab }}</li>
                            @endforeach
                        </ul>
                    </details>
                </div>
                @endif

                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #dcfce7;">
                    <span style="display: block; font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Prod. Creados</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #16a34a;">{{ count(session('import_results')['new_products']) }}</span>
                    @if(count(session('import_results')['new_products']) > 0)
                    <details style="margin-top: 5px; font-size: 0.85rem; color: #4b5563;">
                        <summary style="cursor: pointer; color: #16a34a;">Ver lista</summary>
                        <ul style="margin: 5px 0 0; padding-left: 15px; max-height: 150px; overflow-y: auto;">
                            @foreach(session('import_results')['new_products'] as $prod)
                                <li>{{ $prod }}</li>
                            @endforeach
                        </ul>
                    </details>
                    @endif
                </div>

                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #dcfce7;">
                    <span style="display: block; font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Precios Actualizados</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #f59e0b;">{{ count(session('import_results')['updated_products']) }}</span>
                    @if(count(session('import_results')['updated_products']) > 0)
                    <details style="margin-top: 5px; font-size: 0.85rem; color: #4b5563;">
                        <summary style="cursor: pointer; color: #f59e0b;">Ver lista</summary>
                        <ul style="margin: 5px 0 0; padding-left: 15px; max-height: 150px; overflow-y: auto;">
                            @foreach(session('import_results')['updated_products'] as $prod)
                                <li>{{ $prod }}</li>
                            @endforeach
                        </ul>
                    </details>
                    @endif
                </div>
            </div>
        </div>
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

        @if(request()->filled('laboratory_id') && $products->total() > 0)
            @php
                $selectedLab = $laboratories->firstWhere('id', request('laboratory_id'));
            @endphp
            <div style="margin-top: 20px; padding: 15px; background: #fff1f2; border-radius: 12px; border: 1px solid #fecdd3; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px; color: #9f1239;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.2rem;"></i>
                    <div>
                        <span style="font-weight: 700; display: block;">Zona de Peligro: Laboratorio {{ $selectedLab->descripcion }}</span>
                        <span style="font-size: 0.85rem; opacity: 0.8;">Se han encontrado <b>{{ $products->total() }}</b> productos filtrados. ¿Deseas eliminarlos todos de forma masiva?</span>
                    </div>
                </div>
                <form action="{{ route('admin.products.deleteByLab', request('laboratory_id')) }}" method="POST" onsubmit="return confirm('¿ESTÁS ABSOLUTAMENTE SEGURO? Esta acción eliminará los {{ $products->total() }} productos del laboratorio {{ $selectedLab->descripcion }} permanentemente y no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #e11d48; color: white; font-weight: 700; padding: 10px 20px;">
                        <i class="fas fa-trash-alt"></i> ELIMINAR TODO ({{ $products->total() }})
                    </button>
                </form>
            </div>
        @endif
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
                            <button onclick='openHistoryModal(@json($product->id), @json($product->nombre))' class="btn" style="background: #e0f2fe; color: #0369a1; padding: 6px 10px;" title="Historial de Precios"><i class="fas fa-history"></i></button>
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
        <div class="modal-content" style="max-width: 900px; max-height: 90vh; display: flex; flex-direction: column; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 20px 30px; flex-shrink: 0;">
                <h3 style="color: #ffffff; margin: 0; font-weight: 700;"><i class="fas fa-box-open" style="background: rgba(255,255,255,0.1); color: #10b981;"></i> Registrar Nuevo Producto</h3>
                <span class="close-modal" onclick="closeModal('newProductModal')" style="background: rgba(255,255,255,0.1); color: white;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 0; overflow-y: auto; flex: 1;">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="newProductForm">
                    @csrf
                    <div style="display: flex; flex-direction: column;">
                        <!-- Contenido del formulario -->
                        <div style="flex: 1; padding: 40px;">
                            <!-- Sección: Datos Principales -->
                            <div class="form-section-header" style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0;">
                                <h4 style="margin: 0; font-size: 1.2rem; color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-info-circle" style="color: #10b981;"></i> Información Comercial
                                </h4>
                                <p style="margin: 5px 0 0; font-size: 0.9rem; color: #64748b;">Complete los datos básicos del producto para el catálogo.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr; gap: 25px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Nombre Comercial del Producto</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-tag" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                                        <input type="text" name="nombre" class="form-control" style="padding-left: 45px;" placeholder="Ej. Paracetamol 500mg x 100" required>
                                    </div>
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

                            <!-- Sección: Especificaciones Técnicas -->
                            <div class="form-section-header" style="margin: 40px 0 25px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0;">
                                <h4 style="margin: 0; font-size: 1.2rem; color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-microscope" style="color: #10b981;"></i> Especificaciones Médicas
                                </h4>
                                <p style="margin: 5px 0 0; font-size: 0.9rem; color: #64748b;">Información técnica y regulatoria del producto.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Composición / Activos</label>
                                    <textarea name="composicion" class="form-control" rows="3" placeholder="Detalle los principios activos..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Usos / Indicaciones</label>
                                    <textarea name="usos" class="form-control" rows="3" placeholder="¿Para qué sirve este producto?"></textarea>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 10px;">
                                <label class="form-label" style="font-weight: 700; color: #334155;">Contraindicaciones / Advertencias</label>
                                <textarea name="contraindicaciones" class="form-control" rows="2" placeholder="Advertencias importantes..."></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Registro Sanitario</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-file-medical" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                                        <input type="text" name="registro_sanitario" class="form-control" style="padding-left: 45px;" placeholder="Ej. EE-00412">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Imagen Principal</label>
                                    <input type="file" name="imagen" class="form-control">
                                </div>
                            </div>

                            <div style="background: #f0fdf4; padding: 25px; border-radius: 20px; border: 1px solid #dcfce7; margin-top: 30px;">
                                <label style="display: flex; align-items: center; gap: 20px; cursor: pointer;">
                                    <div style="width: 55px; height: 55px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #16a34a; font-size: 1.6rem; box-shadow: var(--shadow-sm); border: 1px solid #dcfce7;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <span style="display: block; font-weight: 800; color: #166534; font-size: 1.05rem;">Producto Destacado</span>
                                        <span style="display: block; font-size: 0.85rem; color: #14532d; opacity: 0.8;">Aparecerá en la sección principal de la página de inicio.</span>
                                    </div>
                                    <input type="checkbox" name="is_featured" value="1" style="width: 24px; height: 24px; accent-color: #16a34a;">
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
        <div class="modal-content" style="max-width: 900px; max-height: 90vh; display: flex; flex-direction: column; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 20px 30px; flex-shrink: 0;">
                <h3 style="color: #ffffff; margin: 0; font-weight: 700;"><i class="fas fa-edit" style="background: rgba(255,255,255,0.1); color: #f59e0b;"></i> Editar Producto</h3>
                <span class="close-modal" onclick="closeModal('editProductModal')" style="background: rgba(255,255,255,0.1); color: white;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 0; overflow-y: auto; flex: 1;">
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display: flex; flex-direction: column;">
                        <!-- Contenido del formulario -->
                        <div style="flex: 1; padding: 40px;">
                            <!-- Sección: Datos Principales -->
                            <div class="form-section-header" style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0;">
                                <h4 style="margin: 0; font-size: 1.2rem; color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-info-circle" style="color: #f59e0b;"></i> Información Comercial
                                </h4>
                                <p style="margin: 5px 0 0; font-size: 0.9rem; color: #64748b;">Actualice los datos comerciales del producto.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Código SKU</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-barcode" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                                        <input type="text" name="codigo" id="edit-codigo" class="form-control" style="padding-left: 45px; background: #f8fafc;" readonly required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Nombre Comercial</label>
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 10px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Laboratorio</label>
                                    <select name="laboratory_id" id="edit-laboratory_id" class="form-control" required>
                                        @foreach($laboratories as $lab)
                                            <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">U. Medida</label>
                                    <select name="unidad_medida_id" id="edit-unidad_medida_id" class="form-control" required>
                                        @foreach($unidadMedidas as $um)
                                            <option value="{{ $um->id }}">{{ $um->um }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Precio (S/)</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 15px; top: 10px; color: #64748b; font-weight: 600;">S/</span>
                                        <input type="number" step="0.01" name="precio" id="edit-precio" class="form-control" style="padding-left: 35px;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 10px;">
                                <label class="form-label" style="font-weight: 700; color: #334155;">Descripción Corta</label>
                                <textarea name="descripcion" id="edit-descripcion" class="form-control" rows="2"></textarea>
                            </div>

                            <!-- Sección: Especificaciones Técnicas -->
                            <div class="form-section-header" style="margin: 40px 0 25px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0;">
                                <h4 style="margin: 0; font-size: 1.2rem; color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-microscope" style="color: #f59e0b;"></i> Especificaciones Médicas
                                </h4>
                                <p style="margin: 5px 0 0; font-size: 0.9rem; color: #64748b;">Información técnica y regulatoria del producto.</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Composición / Activos</label>
                                    <textarea name="composicion" id="edit-composicion" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Usos / Indicaciones</label>
                                    <textarea name="usos" id="edit-usos" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 10px;">
                                <label class="form-label" style="font-weight: 700; color: #334155;">Contraindicaciones / Advertencias</label>
                                <textarea name="contraindicaciones" id="edit-contraindicaciones" class="form-control" rows="2"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px;">
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Registro Sanitario</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-file-medical" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                                        <input type="text" name="registro_sanitario" id="edit-registro_sanitario" class="form-control" style="padding-left: 45px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 700; color: #334155;">Cambiar Imagen</label>
                                    <input type="file" name="imagen" class="form-control">
                                </div>
                            </div>

                            <div style="background: #fffbeb; padding: 25px; border-radius: 20px; border: 1px solid #fef3c7; margin-top: 30px;">
                                <label style="display: flex; align-items: center; gap: 20px; cursor: pointer;">
                                    <div style="width: 55px; height: 55px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.6rem; box-shadow: var(--shadow-sm); border: 1px solid #fef3c7;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <span style="display: block; font-weight: 800; color: #92400e; font-size: 1.05rem;">Producto Destacado</span>
                                        <span style="display: block; font-size: 0.85rem; color: #92400e; opacity: 0.8;">Aparecerá en la sección principal de la página de inicio.</span>
                                    </div>
                                    <input type="checkbox" name="is_featured" id="edit-is_featured" value="1" style="width: 24px; height: 24px; accent-color: #f59e0b;">
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('editProductModal')">Cancelar</button>
                <button type="submit" form="editProductForm" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
    <!-- Modal Importar Excel -->
    <div id="importExcelModal" class="modal">
        <div class="modal-content" style="max-width: 600px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-excel" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Importar Productos</h3>
                </div>
                <span class="close-modal" onclick="closeModal('importExcelModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 0; overflow-y: auto; flex: 1;">
                <!-- Tabs -->
                <div style="display: flex; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <button type="button" onclick="switchImportTab('lab')" id="tab-lab" style="flex: 1; padding: 15px; border: none; background: white; font-weight: 700; color: #1e293b; border-bottom: 3px solid #10b981; cursor: pointer;">
                        <i class="fas fa-flask"></i> Por Laboratorio
                    </button>
                    <button type="button" onclick="switchImportTab('general')" id="tab-general" style="flex: 1; padding: 15px; border: none; background: transparent; font-weight: 600; color: #64748b; cursor: pointer;">
                        <i class="fas fa-globe"></i> Importación General
                    </button>
                </div>

                <div style="padding: 40px;">
                    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <input type="hidden" name="import_type" id="import_type" value="lab">
                        
                        <div id="lab-selector-container" style="margin-bottom: 25px;">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Seleccionar Laboratorio Destino</label>
                            <div style="position: relative;">
                                <i class="fas fa-flask" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <select name="laboratory_id" id="import_lab_id" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                    <option value="">Seleccione laboratorio</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Archivo Excel (.xlsx, .xls, .csv)</label>
                            <div style="position: relative; border: 2px dashed #e2e8f0; border-radius: 15px; padding: 30px; text-align: center; background: #f8fafc; transition: all 0.3s;" id="drop-zone">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                <span style="color: #64748b; font-size: 0.95rem;">Arrastra tu archivo aquí o haz clic para buscar</span>
                                <input type="file" name="file" id="file-input" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required onchange="updateFileName(this)">
                                <div id="file-name-display" style="margin-top: 10px; font-weight: 700; color: #10b981; display: none;"></div>
                            </div>
                        </div>

                        <div style="background: #f0fdf4; padding: 15px; border-radius: 12px; border: 1px solid #dcfce7;">
                            <p style="margin: 0; font-size: 0.85rem; color: #166534; line-height: 1.5;">
                                <i class="fas fa-info-circle"></i> <b id="import-info-title">Modo Por Laboratorio:</b><br>
                                <span id="import-info-desc">El Excel debe tener las columnas <b>CODIGO, NOMBRE, UM, PRECIO</b>. El laboratorio se asignará automáticamente al seleccionado arriba.</span>
                            </p>
                        </div>

                        <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
                            <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('importExcelModal')">Cancelar</button>
                            <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-file-import" style="margin-right: 8px;"></i> Iniciar Importación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Historial de Precios -->
    <div id="historyModal" class="modal">
        <div class="modal-content" style="max-width: 600px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(3, 105, 161, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-history" style="color: #0ea5e9; font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Historial de Precios</h3>
                        <span id="history-product-name" style="font-size: 0.85rem; color: #94a3b8; font-weight: 500;"></span>
                    </div>
                </div>
                <span class="close-modal" onclick="closeModal('historyModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 30px; overflow-y: auto; flex: 1;">
                <div id="history-content">
                    <!-- Loaded via JS -->
                </div>
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

    function switchImportTab(type) {
        document.getElementById('import_type').value = type;
        const tabLab = document.getElementById('tab-lab');
        const tabGeneral = document.getElementById('tab-general');
        const labSelector = document.getElementById('lab-selector-container');
        const infoTitle = document.getElementById('import-info-title');
        const infoDesc = document.getElementById('import-info-desc');
        const labSelect = document.getElementById('import_lab_id');
        const form = document.getElementById('importForm');

        if (type === 'lab') {
            tabLab.style.background = 'white';
            tabLab.style.borderBottom = '3px solid #10b981';
            tabLab.style.color = '#1e293b';
            tabGeneral.style.background = 'transparent';
            tabGeneral.style.borderBottom = 'none';
            tabGeneral.style.color = '#64748b';
            labSelector.style.display = 'block';
            labSelect.required = true;
            infoTitle.innerText = 'Modo Por Laboratorio:';
            infoDesc.innerHTML = 'El Excel debe tener las columnas <b>CODIGO, NOMBRE, UM, PRECIO</b>. El laboratorio se asignará automáticamente al seleccionado.';
            form.action = "{{ route('admin.products.import') }}";
        } else {
            tabGeneral.style.background = 'white';
            tabGeneral.style.borderBottom = '3px solid #10b981';
            tabGeneral.style.color = '#1e293b';
            tabLab.style.background = 'transparent';
            tabLab.style.borderBottom = 'none';
            tabLab.style.color = '#64748b';
            labSelector.style.display = 'none';
            labSelect.required = false;
            infoTitle.innerText = 'Modo Importación General:';
            infoDesc.innerHTML = 'Este modo soporta el formato original del sistema externo. Extraerá las clases (Laboratorios), descartará los productos especiales (Panetón, Cotización, etc) e importará Unidades de Medida y Precios.';
            form.action = "{{ route('admin.products.import_general') }}";
        }
    }

    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.innerText = 'Archivo seleccionado: ' + input.files[0].name;
            display.style.display = 'block';
        }
    }

    async function openHistoryModal(productId, productName) {
        document.getElementById('history-product-name').innerText = productName;
        document.getElementById('history-content').innerHTML = '<div style="text-align:center; padding:20px;"><i class="fas fa-spinner fa-spin"></i> Cargando historial...</div>';
        openModal('historyModal');

        try {
            const response = await fetch(`/admin/products/${productId}/history`);
            const data = await response.json();
            
            if (data.length === 0) {
                document.getElementById('history-content').innerHTML = '<div style="text-align:center; padding:20px; color:#64748b;">No hay cambios de precio registrados.</div>';
                return;
            }

            let html = `
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Precio Anterior</th>
                            <th>Modificado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(h => `
                            <tr>
                                <td>${new Date(h.created_at).toLocaleString()}</td>
                                <td style="font-weight:700; color:#1e293b;">S/ ${parseFloat(h.precio).toFixed(2)}</td>
                                <td>${h.user ? h.user.name : 'Sistema'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            document.getElementById('history-content').innerHTML = html;
        } catch (error) {
            document.getElementById('history-content').innerHTML = '<div style="text-align:center; padding:20px; color:#ef4444;">Error al cargar el historial.</div>';
        }
    }

    function toggleStatus(id) {
        console.log('Toggling status for product:', id);
    }
</script>
@endpush
