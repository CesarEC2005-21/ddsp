@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Noticias</h2>
        <button onclick="openModal('newNoticiaModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Noticia
        </button>
    </div>

    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.noticias.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ request('codigo') }}" placeholder="Ej. NOT-001">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="{{ request('descripcion') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Fecha Inicial</label>
                    <input type="date" name="fecha_inicial" class="form-control" value="{{ request('fecha_inicial') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Fecha Final</label>
                    <input type="date" name="fecha_final" class="form-control" value="{{ request('fecha_final') }}">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->anyFilled(['codigo', 'descripcion', 'fecha_inicial', 'fecha_final']))
                        <a href="{{ route('admin.noticias.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
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
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Tipo / Laboratorio</th>
                        <th>Descripción</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Autor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($noticias as $noticia)
                    <tr>
                        <td>
                            @if($noticia->imagen)
                                <img src="{{ asset('storage/' . $noticia->imagen) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; cursor: pointer;" onclick="viewImageModal('{{ asset('storage/' . $noticia->imagen) }}', '{{ $noticia->descripcion }}')">
                            @else
                                <div style="width: 50px; height: 50px; background: #f8fafc; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #cbd5e1;"><i class="fas fa-image"></i></div>
                            @endif
                        </td>
                        <td><span class="badge badge-success">{{ $noticia->codigo }}</span></td>
                        <td>
                            <span class="badge {{ $noticia->tipo == 'PROMOCION' ? 'badge-primary' : 'badge-warning' }}" style="display: block; margin-bottom: 5px;">{{ $noticia->tipo }}</span>
                            <span style="font-size: 0.85rem; color: #64748b;"><i class="fas fa-flask"></i> {{ $noticia->laboratory->descripcion ?? 'General' }}</span>
                        </td>
                        <td style="font-weight: 500;">{{ \Str::limit($noticia->descripcion, 50) }}</td>
                        <td style="font-size: 0.85rem;">
                            <i class="far fa-calendar-alt text-muted"></i> {{ \Carbon\Carbon::parse($noticia->fecha_inicial)->format('d/m/Y') }} <br>
                            <i class="far fa-calendar-check text-muted"></i> {{ \Carbon\Carbon::parse($noticia->fecha_final)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($noticia->estado)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: #64748b;"><i class="fas fa-user-edit"></i> {{ $noticia->user->name ?? 'Sistema' }}</td>
                        <td>
                            <button onclick='openEditModal(@json($noticia))' class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $noticia->id }}, '{{ $noticia->codigo }}', 'Noticia', '{{ route('admin.noticias.destroy', $noticia->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 30px; text-align: center; color: #888;">No hay noticias registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
            {{ $noticias->appends(request()->query())->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal Nueva Noticia -->
    <div id="newNoticiaModal" class="modal">
        <div class="modal-content" style="max-width: 850px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-newspaper" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nueva Noticia</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newNoticiaModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form action="{{ route('admin.noticias.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Sección Información -->
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Información de la Noticia
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">Complete los datos básicos de la noticia para el portal.</p>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Título Atractivo del Producto</label>
                            <div style="position: relative;">
                                <i class="fas fa-tag" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="descripcion" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #fcfcfc;" placeholder="Ej. ¡Súper Promoción Pulmol!" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Laboratorio</label>
                                <select name="laboratory_id" class="form-control" style="padding: 12px; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                                    <option value="">Seleccionar...</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Tipo Noticia</label>
                                <select name="tipo" class="form-control" style="padding: 12px; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                                    <option value="PROMOCION">PROMOCIÓN</option>
                                    <option value="EVENTO">EVENTO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Imagen Promo</label>
                                <input type="file" name="imagen" class="form-control" style="padding: 8px; border-radius: 12px;" accept="image/*" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Descripción Detallada</label>
                            <textarea name="detalle" class="form-control" style="border-radius: 15px; padding: 15px; border: 1.5px solid #e2e8f0;" rows="3" placeholder="Resumen detallado para los clientes..."></textarea>
                        </div>
                    </div>

                    <!-- Sección Especificaciones -->
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            Vigencia y Vinculación
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">Información de tiempo y relación con productos.</p>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Producto del Catálogo (Opcional)</label>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 1; position: relative;">
                                        <i class="fas fa-barcode" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                        <input type="hidden" name="product_id" id="new-product-id">
                                        <input type="text" id="new-product-display" class="form-control" style="padding-left: 50px; border-radius: 12px; background: #f8fafc; cursor: pointer;" readonly placeholder="Buscar producto..." onclick="openProductPicker('new')">
                                    </div>
                                    <button type="button" class="btn btn-primary" style="border-radius: 12px; width: 50px;" onclick="openProductPicker('new')">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <label class="form-label" style="color: #475569; font-weight: 600;">Fecha Inicio</label>
                                        <input type="date" name="fecha_inicial" class="form-control" style="border-radius: 12px;" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="color: #475569; font-weight: 600;">Fecha Fin</label>
                                        <input type="date" name="fecha_final" class="form-control" style="border-radius: 12px;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('newNoticiaModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Finalizar y Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Noticia -->
    <div id="editNoticiaModal" class="modal">
        <div class="modal-content" style="max-width: 850px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Noticia</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editNoticiaModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form id="editNoticiaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sección Información -->
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Información de la Noticia
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">Actualice los datos comerciales de la noticia.</p>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Título Atractivo del Producto</label>
                            <div style="position: relative;">
                                <i class="fas fa-tag" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="descripcion" id="edit-descripcion" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0;" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Laboratorio</label>
                                <select name="laboratory_id" id="edit-laboratory_id" class="form-control" style="padding: 12px; border-radius: 12px;">
                                    <option value="">Seleccionar...</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Tipo Noticia</label>
                                <select name="tipo" id="edit-tipo" class="form-control" style="padding: 12px; border-radius: 12px;">
                                    <option value="PROMOCION">PROMOCIÓN</option>
                                    <option value="EVENTO">EVENTO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Actualizar Imagen</label>
                                <input type="file" name="imagen" class="form-control" style="padding: 8px; border-radius: 12px;" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Descripción Detallada</label>
                            <textarea name="detalle" id="edit-detalle" class="form-control" style="border-radius: 15px; padding: 15px;" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Sección Especificaciones -->
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            Vigencia y Vinculación
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Producto Vinculado</label>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 1; position: relative;">
                                        <i class="fas fa-barcode" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                        <input type="hidden" name="product_id" id="edit-product_id">
                                        <input type="text" id="edit-product-display" class="form-control" style="padding-left: 50px; border-radius: 12px; background: #f8fafc;" readonly onclick="openProductPicker('edit')">
                                    </div>
                                    <button type="button" class="btn btn-primary" style="border-radius: 12px; width: 50px;" onclick="openProductPicker('edit')">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <label class="form-label" style="color: #475569; font-weight: 600;">Fecha Inicio</label>
                                        <input type="date" name="fecha_inicial" id="edit-fecha-inicial" class="form-control" style="border-radius: 12px;" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="color: #475569; font-weight: 600;">Fecha Fin</label>
                                        <input type="date" name="fecha_final" id="edit-fecha-final" class="form-control" style="border-radius: 12px;" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fdfaf3; padding: 15px; border-radius: 12px; border: 1.5px solid #fef3c7;">
                                <input type="checkbox" name="estado" id="edit-estado" value="1" style="width: 20px; height: 20px; accent-color: #f59e0b;">
                                <span style="font-weight: 700; color: #92400e;">Publicación Activa</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('editNoticiaModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> </div>
    </div>

    <!-- Modal Buscador de Productos -->
    <div id="productSearchModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3><i class="fas fa-search"></i> Seleccionar Producto</h3>
                <span class="close-modal" onclick="closeModal('productSearchModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group" style="position: relative; margin-bottom: 20px;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                    <input type="text" id="productSearchInput" class="form-control" style="padding-left: 45px;" placeholder="Buscar por código SKU o nombre del producto..." onkeyup="filterProductsInPicker()">
                </div>
                
                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <table class="admin-table" style="margin-bottom: 0;">
                        <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 5;">
                            <tr>
                                <th>Código</th>
                                <th>Nombre del Producto</th>
                                <th>Laboratorio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="productPickerBody">
                            @foreach($products as $prod)
                            <tr class="product-picker-row" data-id="{{ $prod->id }}" data-code="{{ $prod->codigo }}" data-name="{{ $prod->nombre }}">
                                <td><span class="badge badge-success">{{ $prod->codigo }}</span></td>
                                <td style="font-weight: 600;">{{ $prod->nombre }}</td>
                                <td>{{ $prod->laboratory->descripcion ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="selectProductFromPicker({{ $prod->id }}, '{{ $prod->codigo }}', '{{ $prod->nombre }}')">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let currentPickerTarget = 'new'; // 'new' or 'edit'

    function openProductPicker(target) {
        currentPickerTarget = target;
        document.getElementById('productSearchInput').value = '';
        filterProductsInPicker(); // Reset filter
        openModal('productSearchModal');
    }

    function filterProductsInPicker() {
        const input = document.getElementById('productSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.product-picker-row');
        
        rows.forEach(row => {
            const code = row.getAttribute('data-code').toLowerCase();
            const name = row.getAttribute('data-name').toLowerCase();
            if (code.includes(input) || name.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function selectProductFromPicker(id, code, name) {
        if (currentPickerTarget === 'new') {
            document.getElementById('new-product-id').value = id;
            document.getElementById('new-product-display').value = `${code} - ${name}`;
        } else {
            document.getElementById('edit-product_id').value = id;
            document.getElementById('edit-product-display').value = `${code} - ${name}`;
        }
        closeModal('productSearchModal');
    }

    function clearProductPicker(target) {
        if (target === 'new') {
            document.getElementById('new-product-id').value = '';
            document.getElementById('new-product-display').value = '';
        } else {
            document.getElementById('edit-product_id').value = '';
            document.getElementById('edit-product-display').value = '';
        }
    }

    function openEditModal(noticia) {
        const form = document.getElementById('editNoticiaForm');
        form.action = `/admin/noticias/${noticia.id}`;
        
        document.getElementById('edit-tipo').value = noticia.tipo;
        document.getElementById('edit-laboratory_id').value = noticia.laboratory_id || '';
        document.getElementById('edit-descripcion').value = noticia.descripcion;
        document.getElementById('edit-detalle').value = noticia.detalle || '';
        document.getElementById('edit-fecha-inicial').value = noticia.fecha_inicial;
        document.getElementById('edit-fecha-final').value = noticia.fecha_final;
        document.getElementById('edit-estado').checked = !!noticia.estado;

        // Set linked product if exists
        if (noticia.product_id && noticia.product) {
            document.getElementById('edit-product_id').value = noticia.product_id;
            document.getElementById('edit-product-display').value = `${noticia.product.codigo} - ${noticia.product.nombre}`;
        } else {
            document.getElementById('edit-product_id').value = '';
            document.getElementById('edit-product-display').value = '';
        }
        
        openModal('editNoticiaModal');
    }

    function viewImageModal(src, desc) {
        document.getElementById('view-image-src').src = src;
        document.getElementById('view-image-desc').innerText = desc;
        openModal('viewImageModal');
    }
</script>
@endpush
