@extends('layouts.landing')

@push('styles')
<link rel="preload" href="{{ asset('img/hero.png') }}" as="image">
<link rel="stylesheet" href="{{ asset('css/landing/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing/home_labs.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing/home_products.css') }}">
@endpush

@section('content')
    <!-- Hero Carousel Section -->
    <section class="hero-carousel">
        <div class="carousel-container">
            <div class="carousel-slide active">
                <div class="slide-bg" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('img/hero.png') }}');"></div>
                <div class="hero-content">
                    <h1 class="animate-title">Líderes en Distribución de Confianza</h1>
                    <p class="animate-text">Abastecemos al Perú con los más altos estándares de calidad farmacéutica.</p>
                    <div class="hero-btns animate-btns">
                        <a href="{{ route('products') }}" class="btn btn-primary">Nuestros Productos</a>
                        <a href="{{ route('about') }}" class="btn btn-outline">Red de Distribución</a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide">
                <div class="slide-bg" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('img/hero2.png') }}');"></div>
                <div class="hero-content">
                    <h1 class="animate-title">Logística Especializada</h1>
                    <p class="animate-text">Garantizamos la cadena de frío y trazabilidad en cada entrega nacional.</p>
                    <div class="hero-btns animate-btns">
                        <a href="{{ route('about') }}" class="btn btn-primary">Ver Cobertura</a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide">
                <div class="slide-bg" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('img/hero3.png') }}');"></div>
                <div class="hero-content">
                    <h1 class="animate-title">Alianzas que Saludan</h1>
                    <p class="animate-text">Trabajamos con los laboratorios más prestigiosos para cuidar tu salud.</p>
                    <div class="hero-btns animate-btns">
                        <a href="{{ route('contact') }}" class="btn btn-primary">Contáctanos</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carousel Controls -->
        <div class="carousel-controls">
            <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
            <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
        </div>
        
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <span class="indicator active" data-index="0"></span>
            <span class="indicator" data-index="1"></span>
            <span class="indicator" data-index="2"></span>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="stats reveal">
        <div class="stat-item">
            <h3 data-target="15">0</h3>
            <p>Años de Experiencia</p>
        </div>
        <div class="stat-item">
            <h3 data-target="5000">0</h3>
            <p>Productos Activos</p>
        </div>
        <div class="stat-item">
            <h3 data-target="1200">0</h3>
            <p>Clientes Confían</p>
        </div>
        <div class="stat-item">
            <h3 data-target="24">0</h3>
            <p>Atención Continua</p>
        </div>
    </div>

    <!-- Laboratorios Top -->
    <section class="top-laboratories" style="padding: 120px 5% 80px; background: linear-gradient(160deg, #f0fdf4 0%, #f8fafc 40%, #f0fdfa 100%); text-align: center;">
        <div class="labs-section-header" style="margin-bottom: 50px;">
            <div class="section-badge">
                <i class="fas fa-flask"></i> Nuestras Alianzas
            </div>
            <h2>Laboratorios <span>Destacados</span></h2>
            <p>Colaboramos con laboratorios de clase mundial para asegurar el acceso a medicinas de alta calidad en todo el Perú.</p>
        </div>
        
        <div class="labs-grid">
            @forelse($topLaboratories as $lab)
                <a href="{{ route('products', ['lab' => $lab->id]) }}" class="lab-card-link reveal-lab">
                    <div class="lab-card">
                        <div class="lab-logo-container">
                            @if($lab->logo)
                                <img src="{{ asset('storage/' . $lab->logo) }}" alt="{{ $lab->descripcion }}">
                            @else
                                <div class="lab-placeholder">
                                    <i class="fas fa-flask"></i>
                                </div>
                            @endif
                        </div>
                        <div class="lab-info">
                            <h4>{{ $lab->descripcion }}</h4>
                            <span class="lab-action">Ver Catálogo <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="labs-empty">
                    <p>Descubre pronto nuestras marcas aliadas.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products" style="padding: 100px 5%; background: white;">
        <div class="prod-section-header" style="text-align: center; margin-bottom: 50px;">
            <div class="prod-badge">
                <i class="fas fa-star"></i> Selección Especial
            </div>
            <h2>Productos <span>Destacados</span></h2>
            <div class="prod-divider"></div>
        </div>

        <div class="prod-grid">
            @forelse($featuredProducts as $product)
                <div class="prod-card">
                    <a href="{{ route('product.detail', $product->id) }}" class="prod-image-wrap" style="text-decoration: none; display: flex;">
                        <span class="prod-badge"><i class="fas fa-star"></i> DESTACADO</span>
                        @if($product->imagen)
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}">
                        @else
                            <div class="prod-image-placeholder">
                                <i class="fas fa-pills"></i>
                                <span>Sin Imagen</span>
                            </div>
                        @endif
                    </a>
                    <div class="prod-info">
                        <span class="prod-laboratory"><i class="fas fa-flask"></i> {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}</span>
                        <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none;">
                            <h4 class="prod-name">{{ $product->nombre }}</h4>
                        </a>
                        <span class="prod-code">#{{ $product->codigo }}</span>
                        <div class="prod-footer">
                            <span class="prod-price"><span class="currency">S/</span> {{ number_format($product->precio, 2) }}</span>
                            <a href="{{ route('product.detail', $product->id) }}" class="prod-btn">
                                Detalles <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="prod-empty">
                    <p>Pronto verás aquí nuestra selección de productos destacados.</p>
                </div>
            @endforelse
        </div>
        
        <div class="prod-cta">
            <a href="{{ route('products') }}" class="btn-catalog">
                Ver Todo el Catálogo <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- Secciones adicionales (Mapa de Cobertura y Galería) se mantienen iguales con mejoras visuales leves -->
    <section class="coverage-map" style="background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); color: white; padding: 120px 5%; display: flex; align-items: center; justify-content: center; gap: 80px; flex-wrap: wrap; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(white 1px, transparent 1px); background-size: 30px 30px; opacity: 0.1;"></div>
        <div style="max-width: 550px; z-index: 2;">
            <h2 style="font-size: 3.5rem; margin-bottom: 25px; line-height: 1.1; font-family: 'Poppins', sans-serif; font-weight: 800; color: white !important;">Logística de clase mundial a su alcance</h2>
            <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9; line-height: 1.6; color: white;">Nuestro compromiso va más allá de la entrega. Garantizamos la trazabilidad y calidad de cada fármaco mediante una red de representantes altamente capacitados.</p>
            <a href="{{ route('contact') }}" class="btn" style="background: white; color: #1b5e20; padding: 20px 40px; border-radius: 50px; font-weight: 800; text-decoration: none; display: inline-block; box-shadow: 0 15px 30px rgba(0,0,0,0.2); transition: 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-shield-alt"></i> ASEGURAR CALIDAD
            </a>
        </div>
        <div style="position: relative; z-index: 2; width: 550px; height: 550px; display: flex; align-items: center; justify-content: center; margin-right: -5%; transform: scale(1.15);">
            <img src="{{ asset('img/mapa_peru.png') }}" alt="Mapa de Cobertura" style="width: 100%; height: auto; z-index: 3; mix-blend-mode: multiply; filter: contrast(1.1) brightness(1.2);">
        </div>
    </section>

    <section class="gallery reveal" style="padding: 120px 5%; background: #f0fdf4; text-align: center;">
        <h2 style="font-size: 3rem; color: #1e293b; margin-bottom: 20px; font-weight: 800;">Infraestructura de Excelencia</h2>
        <p style="color: #64748b; margin-bottom: 60px; max-width: 800px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">Operamos bajo estrictos protocolos de Buenas Prácticas de Almacenamiento (BPA).</p>
        <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('{{ asset('img/logistica.png') }}');">                <div class="gallery-overlay"><span>Logística Avanzada</span></div>
            </div>
                <div class="gallery-item" style="background-image: url('{{ asset('img/calidad.png') }}');">                <div class="gallery-overlay"><span>Logística Avanzada</span></div>
                <div class="gallery-overlay"><span>Calidad Garantizada</span></div>
            </div>
                <div class="gallery-item" style="background-image: url('{{ asset('img/transporte.png') }}');">                <div class="gallery-overlay"><span>Logística Avanzada</span></div>
                <div class="gallery-overlay"><span>Cobertura Nacional</span></div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Carousel Logic
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');
    const nextBtn = document.querySelector('.next-slide');
    const prevBtn = document.querySelector('.prev-slide');
    let currentSlide = 0;
    let autoPlayInterval;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        indicators.forEach(i => i.classList.remove('active'));
        
        if (index >= slides.length) currentSlide = 0;
        if (index < 0) currentSlide = slides.length - 1;
        
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
    }

    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide--;
        showSlide(currentSlide);
    }

    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoPlay();
    });

    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoPlay();
    });

    indicators.forEach((ind, i) => {
        ind.addEventListener('click', () => {
            currentSlide = i;
            showSlide(currentSlide);
            resetAutoPlay();
        });
    });

    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 6000);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }

    startAutoPlay();

    // Stats Animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const stats = entry.target.querySelectorAll('.stat-item h3');
                stats.forEach(stat => {
                    const target = parseInt(stat.getAttribute('data-target'));
                    let count = 0;
                    const duration = 2000;
                    const step = target / (duration / 16);
                    
                    const updateCount = () => {
                        count += step;
                        if (count < target) {
                            stat.innerText = Math.floor(count);
                            requestAnimationFrame(updateCount);
                        } else {
                            stat.innerText = target + (target > 1000 ? '+' : '');
                        }
                    };
                    updateCount();
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    observer.observe(document.querySelector('.stats'));

    // Reveal on scroll
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    // Staggered labs entrance
    const labsObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const cards = entry.target.querySelectorAll('.lab-card-link');
                cards.forEach((card, i) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(40px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, i * 120);
                });
                labsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    const labsGrid = document.querySelector('.labs-grid');
    if (labsGrid) {
        labsGrid.querySelectorAll('.lab-card-link').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(40px)';
        });
        labsObserver.observe(labsGrid);
    }

    // Staggered featured products entrance
    const prodObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const cards = entry.target.querySelectorAll('.prod-card');
                cards.forEach((card, i) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(40px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, i * 120);
                });
                prodObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    const prodGrid = document.querySelector('.prod-grid');
    if (prodGrid) {
        prodGrid.querySelectorAll('.prod-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(40px)';
        });
        prodObserver.observe(prodGrid);
    }
</script>
@endpush
