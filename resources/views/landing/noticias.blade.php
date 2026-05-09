@extends('layouts.landing')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');

    :root {
        --dark-green-rgb: 27, 94, 32;
        --promo-color: #ef4444;
        --event-color: #f59e0b;
        --card-height: 280px;
    }

    .noticias-hero {
        background: linear-gradient(rgba(var(--dark-green-rgb), 0.85), rgba(var(--dark-green-rgb), 0.95)), 
                    url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        color: #ffffff; 
        text-align: center; 
        padding: 80px 5% 120px; 
        clip-path: ellipse(150% 100% at 50% 0%);
        margin-bottom: -40px;
        position: relative;
        z-index: 1;
    }

    .noticias-hero h1 {
        font-size: 3rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        margin-bottom: 10px;
        color: #ffffff; /* Explicitly white */
    }

    /* Filter Bar */
    .filter-container {
        max-width: 1400px;
        margin: 0 auto 40px;
        padding: 0 5%;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 3;
    }

    .filter-bar {
        background: white;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        display: flex;
        gap: 5px;
        border: 1px solid #e2e8f0;
    }

    .filter-btn {
        padding: 10px 25px;
        border-radius: 50px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.85rem;
    }

    .filter-btn.active {
        background: var(--primary-green);
        color: white;
    }

    .noticias-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        padding: 0 5% 100px;
        max-width: 1300px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    @media (max-width: 1100px) {
        .noticias-grid {
            grid-template-columns: 1fr;
            max-width: 700px;
        }
    }

    .noticia-card {
        display: flex;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        border: 1px solid #e2e8f0;
        height: var(--card-height);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .noticia-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: var(--primary-green);
    }

    .noticia-image-container {
        width: 40%;
        position: relative;
        overflow: hidden;
        background: #f8fafc; /* Subtle background for transparency */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-right: 1px solid #f1f5f9;
    }

    @media (max-width: 600px) {
        .noticia-card {
            flex-direction: column;
            height: auto;
        }
        .noticia-image-container {
            width: 100%;
            height: 250px;
            border-right: none;
            border-bottom: 1px solid #f1f5f9;
        }
    }

    .noticia-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* CRITICAL: Show full image regardless of orientation */
        transition: transform 0.5s;
    }

    .noticia-card:hover .noticia-image {
        transform: scale(1.05);
    }

    .type-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: white;
        z-index: 2;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge-promo { background: var(--promo-color); }
    .badge-event { background: var(--event-color); }

    .noticia-content {
        padding: 20px 25px; /* Reduced padding to give more room */
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Align to top instead of center */
    }

    .noticia-lab {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-green);
        text-transform: uppercase;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .noticia-title {
        color: #1e293b;
        font-size: 1.25rem; /* Slightly smaller for better fit */
        line-height: 1.3;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.4rem; /* Fixed height for 2 lines to prevent layout jump */
    }

    .noticia-dates-box {
        background: #f1f5f9;
        padding: 8px 12px; /* Reduced from 12px 15px */
        border-radius: 12px;
        margin-bottom: 15px; /* Reduced from 20px */
        font-size: 0.8rem; /* Reduced from 0.85rem */
        color: #475569;
        width: fit-content;
    }

    .date-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .date-row:last-child { margin-bottom: 0; }
    .date-row i { color: var(--primary-green); width: 14px; }

    .noticia-btn {
        align-self: flex-start;
        background: var(--primary-green);
        color: #ffffff;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }

    .noticia-card:hover .noticia-btn {
        background: var(--dark-green);
    }

    /* Professional Modal */
    .noticia-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(10px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: 0.4s;
    }

    .noticia-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .noticia-modal-content {
        background: white;
        width: 95%;
        max-width: 1000px;
        border-radius: 30px;
        overflow: hidden;
        position: relative;
        display: flex;
        max-height: 90vh;
        transform: scale(0.9) translateY(20px);
        transition: 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5);
    }

    @media (max-width: 900px) {
        .noticia-modal-content {
            flex-direction: column;
            overflow-y: auto;
        }
    }

    .noticia-modal-overlay.active .noticia-modal-content {
        transform: scale(1) translateY(0);
    }

    .modal-image-pane {
        width: 50%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        border-right: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
        cursor: zoom-in;
    }

    @media (max-width: 900px) {
        .modal-image-pane { width: 100%; height: 400px; padding: 20px; border-right: none; border-bottom: 1px solid #f1f5f9; }
    }

    .modal-image-pane img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        image-rendering: -webkit-optimize-contrast; /* Enhance clarity */
        will-change: transform;
    }

    .modal-image-pane.zoomed {
        cursor: zoom-out;
    }

    .modal-image-pane.zoomed img {
        transform: scale(2.5); /* Deep zoom for detail */
    }

    /* Floating zoom tip */
    .zoom-tip {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        pointer-events: none;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(4px);
    }

    .modal-info-pane {
        width: 50%;
        padding: 40px; /* Reduced from 60px to save space */
        display: flex;
        flex-direction: column;
        background: white;
        overflow-y: auto; /* Allow scrolling if content is too long */
        max-height: 90vh;
    }

    @media (max-width: 900px) {
        .modal-info-pane { width: 100%; padding: 30px; max-height: none; }
    }

    .modal-close-btn {
        position: absolute;
        top: 25px;
        right: 25px;
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        border: none;
        color: #64748b;
        z-index: 10;
    }

    .modal-close-btn:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }

    .modal-lab-tag {
        color: var(--primary-green);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.85rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-title {
        font-size: 2.2rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 30px;
    }

    .modal-desc {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #475569;
        margin-bottom: 40px;
        white-space: pre-line;
    }

    .modal-footer-info {
        margin-top: 20px;
        padding: 20px;
        background: #f0fdf4;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #dcfce7;
    }

    .modal-footer-info i {
        font-size: 1.5rem;
        color: var(--primary-green);
    }

    .modal-footer-info span {
        font-size: 0.9rem;
        color: #166534;
        font-weight: 600;
    }

</style>
@endpush

@section('content')

<div class="noticias-hero">
    <h1>Nuestras Noticias</h1>
    <p>Mantente informado sobre promociones exclusivas y eventos de Sanchez Pharma.</p>
</div>

<div class="filter-container">
    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterNoticias('TODOS', this)">Todos</button>
        <button class="filter-btn" onclick="filterNoticias('PROMOCION', this)">Promociones</button>
        <button class="filter-btn" onclick="filterNoticias('EVENTO', this)">Eventos</button>
    </div>
</div>

<div class="noticias-grid" id="noticias-grid">
    @forelse($noticias as $noticia)
        @php
            \Carbon\Carbon::setLocale('es');
            $fecha_inicio = \Carbon\Carbon::parse($noticia->fecha_inicial)->translatedFormat('d \d\e F, Y');
            $fecha_fin = \Carbon\Carbon::parse($noticia->fecha_final)->translatedFormat('d \d\e F, Y');
            $noticiaData = [
                'tipo' => $noticia->tipo,
                'laboratory' => $noticia->laboratory->descripcion ?? 'Sanchez Pharma',
                'descripcion' => $noticia->descripcion,
                'detalle' => $noticia->detalle,
                'imagen' => asset('storage/' . $noticia->imagen),
                'inicio' => $fecha_inicio,
                'fin' => $fecha_fin,
                'product_url' => $noticia->product_id ? route('product.detail', $noticia->product_id) : null
            ];
        @endphp
        <div class="noticia-card" data-tipo="{{ $noticia->tipo }}" onclick='openNoticiaModal(@json($noticiaData))'>
            <div class="noticia-image-container">
                <div class="type-badge {{ $noticia->tipo == 'PROMOCION' ? 'badge-promo' : 'badge-event' }}">
                    {{ $noticia->tipo }}
                </div>
                @if($noticia->imagen)
                    <img src="{{ asset('storage/' . $noticia->imagen) }}" alt="Noticia" class="noticia-image">
                @else
                    <div style="font-size: 3rem; color: #cbd5e1;"><i class="fas fa-image"></i></div>
                @endif
            </div>
            <div class="noticia-content">
                <div class="noticia-lab">
                    <i class="fas fa-flask"></i> {{ $noticia->laboratory->descripcion ?? 'Sanchez Pharma' }}
                </div>
                <div class="noticia-title">
                    {{ $noticia->descripcion }}
                </div>
                
                <div class="noticia-dates-box">
                    <div class="date-row"><i class="fas fa-calendar-alt"></i> <span><strong>Inicio:</strong> {{ $fecha_inicio }}</span></div>
                    <div class="date-row"><i class="fas fa-flag-checkered"></i> <span><strong>Fin:</strong> {{ $fecha_fin }}</span></div>
                </div>

                <button class="noticia-btn">
                    Ver más <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: white; border-radius: 20px; border: 2px dashed #e2e8f0; color: #94a3b8;">
            <i class="fas fa-newspaper" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <p>No hay noticias disponibles en este momento.</p>
        </div>
    @endforelse
</div>

<!-- Modal Premium -->
<div class="noticia-modal-overlay" id="publicNoticiaModal" onclick="if(event.target === this) closeNoticiaModal()">
    <div class="noticia-modal-content">
        <button class="modal-close-btn" onclick="closeNoticiaModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-image-pane" id="modal-image-pane">
            <img src="" id="modal-image">
            <div class="zoom-tip">
                <i class="fas fa-search-plus"></i> Clic para ampliar
            </div>
        </div>
        <div class="modal-info-pane">
            <div class="modal-lab-tag">
                <i class="fas fa-flask"></i> <span id="modal-lab"></span>
            </div>
            <h2 class="modal-title" id="modal-title"></h2>
            
            <div class="modal-desc" id="modal-desc" style="font-weight: 600; color: #1e293b; margin-bottom: 20px;"></div>
            
            <div style="background: #f8fafc; padding: 25px; border-radius: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                <h4 style="font-size: 0.9rem; text-transform: uppercase; color: #64748b; margin-bottom: 15px; font-weight: 800; letter-spacing: 1px;">Detalles y Condiciones</h4>
                <div class="modal-desc" id="modal-detalle" style="margin-bottom: 0; font-size: 1rem;"></div>
            </div>

            <div class="noticia-dates-box" style="margin-bottom: 30px; width: 100%;">
                <div class="date-row"><i class="fas fa-calendar-alt"></i> <span id="modal-date-start"></span></div>
                <div class="date-row"><i class="fas fa-flag-checkered"></i> <span id="modal-date-end"></span></div>
            </div>
            
            <div style="margin-top: 10px; margin-bottom: 30px; display: none;" id="btn-catalog-container">
                <a href="" id="modal-catalog-btn" class="noticia-btn" style="background: #10b981; text-decoration: none; display: inline-flex; width: fit-content;">
                    <i class="fas fa-shopping-cart"></i> Ver en Catálogo
                </a>
            </div>

            <div class="modal-footer-info">
                <i class="fas fa-info-circle"></i>
                <span>Consulta disponibilidad con tu ejecutivo de confianza o en nuestras sedes oficiales.</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Zoom & Pan Logic
    const imagePane = document.getElementById('modal-image-pane');
    const modalImg = document.getElementById('modal-image');
    let isZoomed = false;

    imagePane.addEventListener('click', () => {
        isZoomed = !isZoomed;
        imagePane.classList.toggle('zoomed', isZoomed);
        if (!isZoomed) {
            modalImg.style.transformOrigin = 'center center';
        }
    });

    imagePane.addEventListener('mousemove', (e) => {
        if (!isZoomed) return;
        
        const rect = imagePane.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        modalImg.style.transformOrigin = `${x}% ${y}%`;
    });

    function filterNoticias(tipo, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cards = document.querySelectorAll('.noticia-card');
        cards.forEach(card => {
            if (tipo === 'TODOS' || card.getAttribute('data-tipo') === tipo) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function openNoticiaModal(data) {
        // Reset zoom state
        isZoomed = false;
        imagePane.classList.remove('zoomed');
        modalImg.style.transformOrigin = 'center center';

        document.getElementById('modal-image').src = data.imagen;
        document.getElementById('modal-lab').innerText = data.laboratory + ' | ' + data.tipo;
        document.getElementById('modal-title').innerText = data.descripcion.substring(0, 80) + (data.descripcion.length > 80 ? '...' : '');
        document.getElementById('modal-desc').innerText = data.descripcion;
        document.getElementById('modal-detalle').innerText = data.detalle || 'No hay detalles adicionales para esta publicación.';
        document.getElementById('modal-date-start').innerHTML = '<strong>Válido desde:</strong> ' + data.inicio;
        document.getElementById('modal-date-end').innerHTML = '<strong>Válido hasta:</strong> ' + data.fin;
        
        const catalogBtnContainer = document.getElementById('btn-catalog-container');
        if (data.product_url) {
            document.getElementById('modal-catalog-btn').href = data.product_url;
            catalogBtnContainer.style.display = 'block';
        } else {
            catalogBtnContainer.style.display = 'none';
        }
        
        document.getElementById('publicNoticiaModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeNoticiaModal() {
        document.getElementById('publicNoticiaModal').classList.remove('active');
        document.body.style.overflow = '';
    }
</script>
@endpush
