@extends('layouts.admin')

@section('content')
<style>
.banners-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: var(--radius-lg);
    padding: 35px 40px;
    margin-bottom: 35px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.banners-header h2 {
    color: white !important;
    font-size: 1.8rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 14px;
}
.banners-header h2 i { color: var(--primary); font-size: 2rem; }
.banners-header p { color: #94a3b8; margin: 6px 0 0 0; font-size: 0.95rem; }
.banners-header .badge-count {
    background: rgba(16,185,129,0.15);
    color: var(--primary);
    padding: 8px 18px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.85rem;
    border: 1px solid rgba(16,185,129,0.2);
}

/* Section card */
.section-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 30px;
}
.section-card:hover { box-shadow: var(--shadow-md); }
.section-card-header {
    padding: 20px 28px;
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 16px;
}
.section-card-header .section-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
    flex-shrink: 0;
}
.section-card-header h4 {
    font-size: 1.15rem;
    margin: 0;
    color: #0f172a;
}
.section-card-header .section-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin: 2px 0 0 0;
}
.section-card-body { padding: 28px; }

/* Image slot */
.image-slot {
    border: 2px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: var(--transition);
    background: #fafbfc;
}
.image-slot:hover { border-color: var(--primary); }
.image-slot.has-image { border-color: #bbf7d0; }
.image-slot-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
    gap: 10px;
}
.image-slot-header .slot-label {
    font-weight: 700;
    font-size: 0.85rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.image-slot-header .slot-label i { color: var(--primary); font-size: 0.95rem; }
.image-slot-header .slot-status {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 4px 12px;
    border-radius: 50px;
    white-space: nowrap;
}
.status-active { background: #d1fae5; color: #059669; }
.status-empty { background: #f1f5f9; color: #94a3b8; }
.image-slot-preview {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}
.image-slot-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}
.image-slot-preview .no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #cbd5e1;
}
.image-slot-preview .no-image i { font-size: 2.5rem; }
.image-slot-preview .no-image span { font-size: 0.8rem; font-weight: 600; }
.image-slot-footer {
    padding: 12px 16px;
    background: #f8fafc;
    border-top: 1px solid var(--border);
}
.image-slot-footer input[type="file"] {
    font-size: 0.8rem;
    width: 100%;
}
.image-slot-footer input[type="file"]::file-selector-button {
    background: white;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 0.78rem;
    color: #475569;
    cursor: pointer;
    transition: var(--transition);
    margin-right: 10px;
}
.image-slot-footer input[type="file"]::file-selector-button:hover {
    background: #f1f5f9;
    border-color: var(--primary);
    color: var(--primary-dark);
}

.section-divider {
    border: 0;
    height: 1px;
    background: linear-gradient(to right, var(--border), transparent);
    margin: 28px 0;
}

@media (max-width: 768px) {
    .banners-header { padding: 25px 20px; }
    .banners-header h2 { font-size: 1.4rem; }
    .section-card-body { padding: 16px; }
}
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="banners-header">
        <div>
            <h2><i class="fas fa-images"></i> Gestión de Banners</h2>
            <p>Administra todas las imágenes del sitio web. Cada sección tiene espacios específicos que puedes personalizar.</p>
        </div>
        <div class="badge-count">
            <i class="fas fa-layer-group me-1"></i> {{ $banners->count() }} secciones
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $sectionMeta = [
            'inicio' => ['icon' => 'fa-home', 'color' => '#10b981', 'desc' => 'Hero slides y galería de la página principal'],
            'nosotros' => ['icon' => 'fa-building', 'color' => '#3b82f6', 'desc' => 'Banner principal e historia, misión y visión'],
            'novedades' => ['icon' => 'fa-newspaper', 'color' => '#f59e0b', 'desc' => 'Imagen de fondo del encabezado de Novedades'],
            'ejecutivos' => ['icon' => 'fa-briefcase', 'color' => '#8b5cf6', 'desc' => 'Imagen de fondo del encabezado de ejecutivos'],
            'productos' => ['icon' => 'fa-pills', 'color' => '#ef4444', 'desc' => 'Imagen de fondo del encabezado del catálogo'],
            'contacto' => ['icon' => 'fa-envelope', 'color' => '#ec4899', 'desc' => 'Imagen de fondo del encabezado de contacto'],
        ];

        $fieldMeta = [
            'image_path' => ['label' => 'Banner Principal', 'icon' => 'fa-image', 'desc' => 'Imagen de fondo del encabezado de la sección'],
            'hero_image_2' => ['label' => 'Hero Slide 2', 'icon' => 'fa-sliders-h', 'desc' => 'Segunda imagen del carrusel principal'],
            'hero_image_3' => ['label' => 'Hero Slide 3', 'icon' => 'fa-sliders-h', 'desc' => 'Tercera imagen del carrusel principal'],
            'gallery_image_1' => ['label' => 'Galería - Infraestructura 1', 'icon' => 'fa-images', 'desc' => 'Imagen de logística y almacenamiento'],
            'gallery_image_2' => ['label' => 'Galería - Infraestructura 2', 'icon' => 'fa-images', 'desc' => 'Imagen de control de calidad'],
            'gallery_image_3' => ['label' => 'Galería - Infraestructura 3', 'icon' => 'fa-images', 'desc' => 'Imagen de cobertura de transporte'],
            'historia_image'  => ['label' => 'Historia — 2022', 'icon' => 'fa-calendar-alt', 'desc' => 'Imagen correspondiente al año de fundación 2022'],
            'historia_2022_image' => ['label' => 'Historia — 2026', 'icon' => 'fa-calendar-alt', 'desc' => 'Imagen del año actual 2026'],
            'historia_2023_image' => ['label' => 'Historia — 2023', 'icon' => 'fa-calendar-alt', 'desc' => 'Imagen del año de expansión 2023'],
            'historia_2024_image' => ['label' => 'Historia — 2024', 'icon' => 'fa-calendar-alt', 'desc' => 'Imagen del año de consolidación 2024'],
            'historia_2025_image' => ['label' => 'Historia — 2025', 'icon' => 'fa-calendar-alt', 'desc' => 'Imagen del año de liderazgo 2025'],
            'mision_image'   => ['label' => 'Misión', 'icon' => 'fa-bullseye', 'desc' => 'Imagen lateral en la sección de misión'],
            'vision_image'   => ['label' => 'Visión', 'icon' => 'fa-eye', 'desc' => 'Imagen lateral en la sección de visión'],
        ];
    @endphp

    <!-- Sections -->
    @php
        $fullWidthSections = ['inicio', 'nosotros'];
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(500px, 1fr)); gap: 30px;">
    @foreach($banners as $section => $banner)
        @php
            $meta = $sectionMeta[$section] ?? ['icon' => 'fa-circle', 'color' => '#64748b', 'desc' => ''];
            $fieldCount = count($sectionFields[$section]);
            $isFullWidth = $fieldCount > 2;
        @endphp
        <div class="section-card" style="{{ $isFullWidth ? 'grid-column: 1 / -1;' : '' }}">
            <div class="section-card-header">
                <div class="section-icon" style="background: {{ $meta['color'] }};">
                    <i class="fas {{ $meta['icon'] }}"></i>
                </div>
                <div>
                    <h4>{{ ucfirst($section) }}</h4>
                    <p class="section-desc">{{ $meta['desc'] }}</p>
                </div>
            </div>
            <div class="section-card-body">
                <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        @foreach($sectionFields[$section] as $field)
                            @php $fm = $fieldMeta[$field] ?? ['label' => ucfirst(str_replace('_', ' ', $field)), 'icon' => 'fa-image', 'desc' => '']; @endphp
                            <div class="image-slot {{ $banner->$field ? 'has-image' : '' }}">
                                <div class="image-slot-header">
                                    <span class="slot-label">
                                        <i class="fas {{ $fm['icon'] }}"></i> {{ $fm['label'] }}
                                    </span>
                                    <span class="slot-status {{ $banner->$field ? 'status-active' : 'status-empty' }}">
                                        <i class="fas fa-{{ $banner->$field ? 'check-circle' : 'clock' }} me-1"></i>
                                        {{ $banner->$field ? 'Activo' : 'Sin imagen' }}
                                    </span>
                                </div>

                                <div class="image-slot-preview">
                                    @if($banner->$field)
                                        <img src="{{ asset('storage/' . $banner->$field) }}" alt="{{ $fm['label'] }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <span>Sin imagen asignada</span>
                                            <span style="font-size:0.7rem;color:#e2e8f0;">{{ $fm['desc'] }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="image-slot-footer">
                                    <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/jpg,image/webp" id="file_{{ $section }}_{{ $field }}">
                                    <small style="color: #94a3b8; display: block; margin-top: 4px;">
                                        <i class="fas fa-info-circle me-1"></i> {{ $fm['desc'] }}
                                    </small>
                                    @error($field)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="section-divider">

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 36px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-save"></i> Guardar imágenes de {{ ucfirst($section) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    </div>
</div>
@push('scripts')
<script>
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const preview = this.closest('.image-slot').querySelector('.image-slot-preview');
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:contain;padding:10px;">`;
                };
                reader.readAsDataURL(this.files[0]);

                const status = this.closest('.image-slot').querySelector('.slot-status');
                status.className = 'slot-status status-active';
                status.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Por guardar';
            }
        });
    });
</script>
@endpush
@endsection
