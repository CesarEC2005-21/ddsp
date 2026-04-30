@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing/home_labs.css') }}">
@endpush

@section('content')
    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1 style="font-size: 4rem; line-height: 1.1; margin-bottom: 25px;">Expertos en Distribución Farmacéutica</h1>
            <p style="font-size: 1.3rem; margin-bottom: 40px; opacity: 0.9;">Droguería Sanchez Pharma: Calidad, Eficiencia y Cobertura Nacional.</p>
            <div class="hero-btns">
                <a href="{{ route('products') }}" class="btn btn-primary" style="padding: 18px 40px; border-radius: 50px;">Ver Productos</a>
                <a href="{{ route('about') }}" class="btn btn-outline" style="padding: 18px 40px; border-radius: 50px; border-color: white; color: white;">Nuestra Red</a>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <div class="stats reveal" style="margin-top: -60px; position: relative; z-index: 5;">
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
    <section class="top-laboratories" style="padding: 120px 5% 80px; background: white; text-align: center;">
        <span style="color: var(--primary-green); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">Nuestras Alianzas</span>
        <h2 style="font-size: 3rem; color: #1e293b; margin: 15px 0 20px;">Laboratorios Destacados</h2>
        <p style="color: #64748b; margin-bottom: 60px; max-width: 700px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">Colaboramos con laboratorios de clase mundial para asegurar el acceso a medicinas de alta calidad en todo el Perú.</p>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 35px; max-width: 1400px; margin: 0 auto;">
            @forelse($topLaboratories as $lab)
                <div class="lab-card">
                    <div class="lab-logo-container">
                        @if($lab->logo)
                            <img src="{{ asset('storage/' . $lab->logo) }}" alt="{{ $lab->descripcion }}">
                        @else
                            <i class="fas fa-flask" style="font-size: 3.5rem; color: #f1f5f9;"></i>
                        @endif
                    </div>
                    <h4 style="margin: 0; color: #1e293b; font-size: 1.1rem; font-weight: 700;">{{ $lab->descripcion }}</h4>
                </div>
            @empty
                <div style="padding: 40px; background: #f8fafc; border-radius: 20px; width: 100%; border: 2px dashed #e2e8f0;">
                    <p style="color: #94a3b8; margin: 0; font-weight: 600;">Descubre pronto nuestras marcas aliadas.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Secciones adicionales (Mapa de Cobertura y Galería) se mantienen iguales con mejoras visuales leves -->
    <section class="coverage-map" style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); color: white; padding: 120px 5%; display: flex; align-items: center; justify-content: center; gap: 80px; flex-wrap: wrap; position: relative;">
        <div style="max-width: 550px; z-index: 2;">
            <h2 style="font-size: 3.5rem; margin-bottom: 25px; line-height: 1.1; font-family: 'Poppins', sans-serif; font-weight: 800;">Logística de clase mundial a su alcance</h2>
            <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9; line-height: 1.6;">Nuestro compromiso va más allá de la entrega. Garantizamos la trazabilidad y calidad de cada fármaco mediante una red de representantes altamente capacitados.</p>
            <a href="{{ route('contact') }}" class="btn" style="background: white; color: #1b5e20; padding: 20px 40px; border-radius: 50px; font-weight: 800; text-decoration: none; display: inline-block; box-shadow: 0 15px 30px rgba(0,0,0,0.2); transition: 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-shield-alt"></i> ASEGURAR CALIDAD
            </a>
        </div>
        <div style="position: relative; z-index: 2; width: 400px; height: 400px; display: flex; align-items: center; justify-content: center;">
            <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; background: rgba(255,255,255,0.1); animation: pulse 3s infinite;"></div>
            <i class="fas fa-map-marked-alt" style="font-size: 12rem; color: #fbbf24; filter: drop-shadow(0 10px 20px rgba(251, 191, 36, 0.4));"></i>
        </div>
    </section>

    <section class="gallery" style="padding: 120px 5%; background: #fdfdfd; text-align: center;">
        <h2 style="font-size: 3rem; color: #1e293b; margin-bottom: 20px; font-weight: 800;">Infraestructura de Excelencia</h2>
        <p style="color: #64748b; margin-bottom: 60px; max-width: 800px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">Operamos bajo estrictos protocolos de Buenas Prácticas de Almacenamiento (BPA) para proteger la integridad de cada producto.</p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; max-width: 1400px; margin: 0 auto;">
            <div style="height: 400px; border-radius: 25px; background-image: url('https://images.unsplash.com/photo-1587370560942-ad2a04eabb6d?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; box-shadow: 0 15px 30px rgba(0,0,0,0.1); border: 5px solid white;"></div>
            <div style="height: 400px; border-radius: 25px; background-image: url('https://images.unsplash.com/photo-1563213126-a4273aed2016?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; box-shadow: 0 15px 30px rgba(0,0,0,0.1); border: 5px solid white;"></div>
            <div style="height: 400px; border-radius: 25px; background-image: url('https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; box-shadow: 0 15px 30px rgba(0,0,0,0.1); border: 5px solid white;"></div>
        </div>
    </section>
@endsection
