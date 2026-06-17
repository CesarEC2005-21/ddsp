@extends('layouts.landing')

@push('styles')
<style>
/* ======================================================
   NOSOTROS — Design System
   ====================================================== */
   @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

:root {
    --green-100: #dcfce7;
    --green-500: #22c55e;
    --green-600: #16a34a;
    --green-700: #15803d;
    --green-800: #166534;
    --green-900: #14532d;
    --slate-50:  #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-400: #94a3b8;
    --slate-600: #475569;
    --slate-700: #334155;
    --slate-800: #1e293b;
    --slate-900: #0f172a;
}

/* ── Scroll reveal ── */
.sr { opacity: 0; transform: translateY(50px); transition: opacity .8s cubic-bezier(.215,.61,.355,1), transform .8s cubic-bezier(.215,.61,.355,1); }
.sr.up   { transform: translateY(50px); }
.sr.left { transform: translateX(-60px); }
.sr.right{ transform: translateX(60px); }
.sr.scale{ transform: scale(.85); }
.sr.in   { opacity: 1 !important; transform: none !important; }

/* ══════════════════════════════════════════════════════
   1. HERO — Parallax + Particles
   ══════════════════════════════════════════════════════ */
.ns-hero {
    position: relative;
    min-height: 60vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: var(--slate-900, #0f172a);
}
.ns-hero-bg {
    position: absolute; inset: 0;
    background:
        url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : "https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?auto=format&fit=crop&w=1920&q=80" }}')
        center/cover no-repeat;
    filter: brightness(.35) saturate(1.2);
    transform: scale(1.08);
    transition: transform 12s ease-out;
}
.ns-hero-bg.loaded { transform: scale(1); }
.ns-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(34,197,94,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(34,197,94,.08) 1px, transparent 1px);
    background-size: 60px 60px;
    animation: gridFloat 20s linear infinite;
}
@keyframes gridFloat { to { background-position: 60px 60px; } }

.ns-hero-glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: .45;
    animation: glowPulse 6s ease-in-out infinite alternate;
}
.ns-hero-glow.g1 { width: 500px; height: 500px; background: radial-gradient(circle, #22c55e, transparent); top: -150px; right: -100px; }
.ns-hero-glow.g2 { width: 400px; height: 400px; background: radial-gradient(circle, #059669, transparent); bottom: -100px; left: -80px; animation-delay: -3s; }
@keyframes glowPulse { from { opacity: .3; transform: scale(.9); } to { opacity: .6; transform: scale(1.1); } }

#particleCanvas { position: absolute; inset: 0; pointer-events: none; }

.ns-hero-inner {
    position: relative; z-index: 10;
    text-align: center;
    padding: 0 20px;
    max-width: 900px;
}
.ns-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(34,197,94,.15);
    border: 1px solid rgba(34,197,94,.4);
    color: #4ade80;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 28px;
    backdrop-filter: blur(10px);
    animation: badgePop .6s .3s both cubic-bezier(.175,.885,.32,1.275);
}
@keyframes badgePop { from { opacity: 0; transform: scale(.8) translateY(10px); } to { opacity: 1; transform: none; } }

.ns-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900;
    color: white;
    line-height: 1;
    margin-bottom: 12px;
    animation: heroTitle .9s .5s both;
}
@keyframes heroTitle { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }

.ns-hero-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 50%, #a3e635 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
}
.ns-hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: rgba(255,255,255,.7);
    max-width: 600px;
    margin: 24px auto 48px;
    line-height: 1.7;
    animation: heroTitle 1s .7s both;
}

.ns-hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
    animation: heroTitle 1s .9s both;
}
.ns-hstat {
    text-align: center;
    position: relative;
}
.ns-hstat::after {
    content: '';
    position: absolute;
    right: -20px; top: 20%;
    height: 60%;
    width: 1px;
    background: rgba(255,255,255,.2);
}
.ns-hstat:last-child::after { display: none; }
.ns-hstat-num {
    font-family: 'Outfit', sans-serif;
    font-size: 2.8rem;
    font-weight: 900;
    background: linear-gradient(135deg, #4ade80, #22c55e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.ns-hstat-label { color: rgba(255,255,255,.6); font-size: .85rem; margin-top: 6px; font-weight: 500; }

.ns-hero-scroll {
    position: absolute; bottom: 40px; left: 0; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    color: rgba(255,255,255,.5); font-size: .75rem; letter-spacing: 2px; text-transform: uppercase;
    animation: heroTitle 1s 1s both;
}
.ns-scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(34,197,94,.8), transparent);
    animation: scrollLine 2s ease-in-out infinite;
}
@keyframes scrollLine { 0%,100%{ opacity: .3; transform: scaleY(.3) translateY(-10px); } 50%{ opacity: 1; transform: scaleY(1) translateY(0); } }

/* ══════════════════════════════════════════════════════
   2. SECTION UTILITY
   ══════════════════════════════════════════════════════ */
.ns-section { padding: 100px 5%; }
.ns-section.light { background: var(--slate-50); }
.ns-section.dark  { background: var(--slate-900); }

.ns-label {
    display: inline-flex; align-items: center; gap: 8px;
    color: var(--green-600);
    font-size: .75rem; font-weight: 800;
    letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 16px;
}
.ns-label::before {
    content: '';
    display: block;
    width: 24px; height: 2px;
    background: var(--green-500);
    border-radius: 2px;
}

.ns-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 900;
    color: var(--slate-800);
    line-height: 1.1;
}
.ns-title .accent { color: var(--green-600); }
.ns-title.light-text { color: white; }

.ns-divider {
    width: 60px; height: 4px;
    background: linear-gradient(90deg, var(--green-500), var(--green-700));
    border-radius: 4px;
    margin: 20px 0 32px;
}
.ns-divider.center { margin: 20px auto 32px; }

/* ══════════════════════════════════════════════════════
   3. HISTORIA — Timeline
   ══════════════════════════════════════════════════════ */
.ns-historia-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    max-width: 1300px;
    margin: 0 auto;
}
.ns-historia-img {
    position: relative;
}
.ns-historia-img-main {
    width: 100%;
    height: 520px;
    object-fit: cover;
    border-radius: 32px;
    display: block;
    box-shadow: 0 40px 80px rgba(0,0,0,.18);
}
.ns-historia-deco {
    position: absolute;
    inset: -16px -16px -16px -16px;
    border: 3px solid transparent;
    border-image: linear-gradient(135deg, var(--green-500), transparent 60%) 1;
    border-radius: 40px;
    pointer-events: none;
}
.ns-historia-badge-float {
    position: absolute;
    bottom: 32px; right: -28px;
    background: white;
    border-radius: 20px;
    padding: 20px 28px;
    box-shadow: 0 20px 50px rgba(0,0,0,.15);
    text-align: center;
    border-left: 4px solid var(--green-500);
}
.ns-historia-badge-float strong {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--green-600);
    line-height: 1;
}
.ns-historia-badge-float span {
    font-size: .8rem;
    color: var(--slate-600);
    font-weight: 600;
}

.ns-historia-content .ns-body {
    color: var(--slate-600);
    font-size: 1.05rem;
    line-height: 1.85;
    margin-bottom: 16px;
}

.ns-timeline {
    margin-top: 40px;
    position: relative;
    padding-left: 28px;
}
.ns-timeline::before {
    content: '';
    position: absolute;
    left: 7px; top: 8px;
    width: 2px;
    bottom: 8px;
    background: linear-gradient(to bottom, var(--green-500), var(--green-700));
    border-radius: 2px;
}
.ns-tl-item {
    position: relative;
    margin-bottom: 28px;
    padding-left: 20px;
}
.ns-tl-item::before {
    content: '';
    position: absolute;
    left: -28px; top: 6px;
    width: 14px; height: 14px;
    border-radius: 50%;
    background: var(--green-500);
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--green-500);
    transition: all .3s;
}
.ns-tl-item:hover::before {
    transform: scale(1.3);
    box-shadow: 0 0 0 5px rgba(34,197,94,.3);
}
.ns-tl-year {
    font-size: .75rem;
    font-weight: 800;
    color: var(--green-600);
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.ns-tl-text {
    font-size: .95rem;
    color: var(--slate-700);
    font-weight: 500;
    line-height: 1.5;
}

/* ══════════════════════════════════════════════════════
   4. MISIÓN / VISIÓN — Glassmorphism Split
   ══════════════════════════════════════════════════════ */
.ns-mv-section {
    background: linear-gradient(135deg, var(--slate-900) 0%, #0d2818 50%, var(--slate-900) 100%);
    position: relative;
    overflow: hidden;
}
.ns-mv-section::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 70% at 15% 50%, rgba(34,197,94,.12), transparent),
        radial-gradient(ellipse 50% 60% at 85% 50%, rgba(5,150,105,.1), transparent);
}
.ns-mv-section::after {
    content: '';
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(34,197,94,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(34,197,94,.04) 1px, transparent 1px);
    background-size: 40px 40px;
}
.ns-mv-inner {
    position: relative; z-index: 2;
    max-width: 1300px;
    margin: 0 auto;
}
.ns-mv-header { text-align: center; margin-bottom: 64px; }

.ns-mv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}
.ns-mv-card {
    background: rgba(255,255,255,.04);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 28px;
    padding: 48px 40px;
    position: relative;
    overflow: hidden;
    transition: transform .4s ease, box-shadow .4s ease, border-color .4s ease;
}
.ns-mv-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 40px 80px rgba(0,0,0,.3), inset 0 1px 0 rgba(255,255,255,.15);
    border-color: rgba(34,197,94,.35);
}
.ns-mv-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    right: 0; height: 3px;
    background: linear-gradient(90deg, var(--green-500), var(--green-700));
    border-radius: 28px 28px 0 0;
}
.ns-mv-card-icon {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, rgba(34,197,94,.2), rgba(5,150,105,.15));
    border: 1px solid rgba(34,197,94,.3);
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
    color: #4ade80;
    margin-bottom: 28px;
    transition: all .4s;
}
.ns-mv-card:hover .ns-mv-card-icon {
    background: linear-gradient(135deg, rgba(34,197,94,.35), rgba(5,150,105,.25));
    transform: rotate(5deg) scale(1.1);
    box-shadow: 0 12px 30px rgba(34,197,94,.3);
}
.ns-mv-card-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.7rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
}
.ns-mv-card-text {
    font-size: 1rem;
    color: rgba(255,255,255,.65);
    line-height: 1.85;
}
.ns-mv-card-bg-num {
    position: absolute;
    bottom: -20px; right: 20px;
    font-family: 'Outfit', sans-serif;
    font-size: 10rem;
    font-weight: 900;
    color: rgba(255,255,255,.025);
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

/* ══════════════════════════════════════════════════════
   5. VALORES — 3D Flip Cards
   ══════════════════════════════════════════════════════ */
.ns-valores-section { background: white; }
.ns-valores-header { text-align: center; margin-bottom: 64px; }
.ns-valores-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
    max-width: 1300px;
    margin: 0 auto;
}

.ns-flip-card {
    height: 240px;
    perspective: 1000px;
    cursor: pointer;
}
.ns-flip-inner {
    position: relative;
    width: 100%; height: 100%;
    transition: transform .7s cubic-bezier(.175,.885,.32,1.275);
    transform-style: preserve-3d;
}
.ns-flip-card:hover .ns-flip-inner { transform: rotateY(180deg); }

.ns-flip-front, .ns-flip-back {
    position: absolute; inset: 0;
    border-radius: 24px;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 28px 20px;
    text-align: center;
}
.ns-flip-front {
    background: var(--slate-50);
    border: 1.5px solid var(--slate-200);
    transition: border-color .3s, box-shadow .3s;
}
.ns-flip-card:hover .ns-flip-front {
    border-color: var(--green-500);
    box-shadow: 0 20px 50px rgba(34,197,94,.12);
}
.ns-flip-back {
    background: linear-gradient(135deg, var(--green-700), var(--green-900));
    transform: rotateY(180deg);
    box-shadow: 0 20px 50px rgba(22,101,52,.3);
}

.ns-flip-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: white;
    margin-bottom: 18px;
    box-shadow: 0 10px 25px rgba(34,197,94,.35);
    transition: transform .3s;
}
.ns-flip-card:not(:hover) .ns-flip-front:hover .ns-flip-icon { transform: scale(1.1) rotate(-5deg); }

.ns-flip-label {
    font-family: 'Outfit', sans-serif;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--slate-800);
}
.ns-flip-back-icon {
    font-size: 2.5rem; color: rgba(255,255,255,.3); margin-bottom: 16px;
}
.ns-flip-back-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: white;
    margin-bottom: 12px;
}
.ns-flip-back-text {
    font-size: .85rem;
    color: rgba(255,255,255,.75);
    line-height: 1.6;
}

/* ══════════════════════════════════════════════════════
   6. PRINCIPIOS — Accordion + Visual
   ══════════════════════════════════════════════════════ */
.ns-principios-section { background: var(--slate-50); }
.ns-principios-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: start;
    max-width: 1300px;
    margin: 0 auto;
}
.ns-accordion { display: flex; flex-direction: column; gap: 12px; }
.ns-acc-item {
    background: white;
    border-radius: 16px;
    border: 1.5px solid var(--slate-200);
    overflow: hidden;
    transition: border-color .3s, box-shadow .3s;
}
.ns-acc-item.open {
    border-color: var(--green-500);
    box-shadow: 0 8px 30px rgba(34,197,94,.1);
}
.ns-acc-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    cursor: pointer;
    user-select: none;
}
.ns-acc-num {
    width: 36px; height: 36px;
    background: var(--green-100);
    color: var(--green-700);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .9rem;
    flex-shrink: 0;
    transition: background .3s, color .3s;
}
.ns-acc-item.open .ns-acc-num {
    background: var(--green-600);
    color: white;
}
.ns-acc-title {
    flex: 1;
    font-size: 1rem;
    font-weight: 700;
    color: var(--slate-700);
}
.ns-acc-chevron {
    color: var(--slate-400);
    transition: transform .3s;
    font-size: .85rem;
}
.ns-acc-item.open .ns-acc-chevron { transform: rotate(180deg); color: var(--green-600); }
.ns-acc-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .4s ease;
}
.ns-acc-body-inner {
    padding: 0 24px 20px 76px;
    font-size: .95rem;
    color: var(--slate-600);
    line-height: 1.7;
}

.ns-principios-visual {
    position: sticky;
    top: 120px;
}
.ns-pv-card {
    background: linear-gradient(135deg, var(--green-800), var(--green-900));
    border-radius: 32px;
    padding: 48px 40px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 40px 80px rgba(22,101,52,.3);
}
.ns-pv-card::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.ns-pv-card::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(34,197,94,.08);
}
.ns-pv-content { position: relative; z-index: 2; }
.ns-pv-icon {
    width: 80px; height: 80px;
    background: rgba(34,197,94,.2);
    border: 1px solid rgba(34,197,94,.4);
    border-radius: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #4ade80;
    margin-bottom: 32px;
}
.ns-pv-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    color: white;
    margin-bottom: 16px;
    line-height: 1.2;
}
.ns-pv-body {
    color: rgba(255,255,255,.65);
    font-size: 1rem;
    line-height: 1.75;
    margin-bottom: 36px;
}
.ns-pv-metrics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.ns-pv-metric {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}
.ns-pv-metric strong {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    color: #4ade80;
    line-height: 1;
    margin-bottom: 6px;
}
.ns-pv-metric span {
    font-size: .78rem;
    color: rgba(255,255,255,.55);
    font-weight: 600;
    line-height: 1.3;
}

/* ══════════════════════════════════════════════════════
   7. CTA — Premium
   ══════════════════════════════════════════════════════ */
.ns-cta {
    background: var(--slate-900);
    position: relative;
    overflow: hidden;
    text-align: center;
    padding: 120px 5%;
}
.ns-cta::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 50% 50%, rgba(34,197,94,.12), transparent);
}
.ns-cta-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(34,197,94,.08);
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    animation: ringExpand 4s ease-out infinite;
}
.ns-cta-ring:nth-child(2) { animation-delay: 1.3s; }
.ns-cta-ring:nth-child(3) { animation-delay: 2.6s; }
@keyframes ringExpand {
    0%   { width: 200px; height: 200px; opacity: .5; }
    100% { width: 900px; height: 900px; opacity: 0; }
}
.ns-cta-inner { position: relative; z-index: 5; max-width: 800px; margin: 0 auto; }
.ns-cta-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.2rem, 5vw, 4rem);
    font-weight: 900;
    color: white;
    margin-bottom: 20px;
    line-height: 1.1;
}
.ns-cta-title .hl {
    background: linear-gradient(135deg, #4ade80, #22c55e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.ns-cta-sub {
    font-size: 1.1rem;
    color: rgba(255,255,255,.6);
    max-width: 520px;
    margin: 0 auto 48px;
    line-height: 1.7;
}
.ns-cta-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}
.ns-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 18px 40px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
    transition: all .35s cubic-bezier(.4,0,.2,1);
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.ns-btn::before {
    content: '';
    position: absolute; inset: 0;
    background: rgba(255,255,255,.15);
    opacity: 0;
    transition: opacity .3s;
}
.ns-btn:hover::before { opacity: 1; }
.ns-btn-primary {
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    color: white;
    box-shadow: 0 10px 30px rgba(34,197,94,.35);
}
.ns-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 50px rgba(34,197,94,.5);
}
.ns-btn-outline {
    background: transparent;
    color: white;
    border: 1.5px solid rgba(255,255,255,.25);
}
.ns-btn-outline:hover {
    border-color: var(--green-500);
    color: #4ade80;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(34,197,94,.15);
}

/* ══════════════════════════════════════════════════════
   8. RESPONSIVE
   ══════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
    .ns-valores-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 900px) {
    .ns-historia-wrap,
    .ns-mv-grid,
    .ns-principios-wrap { grid-template-columns: 1fr; gap: 48px; }
    .ns-historia-badge-float { right: 16px; }
    .ns-hstat::after { display: none; }
    .ns-principios-visual { position: static; }
    .ns-valores-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .ns-valores-grid { grid-template-columns: 1fr; }
    .ns-hero-stats { gap: 24px; }
    .ns-flip-card { height: 200px; }
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════
     1. HERO
     ═══════════════════════════════════════════════════ --}}
<section class="ns-hero">
    <div class="ns-hero-bg" id="heroBg"></div>
    <div class="ns-hero-grid"></div>
    <div class="ns-hero-glow g1"></div>
    <div class="ns-hero-glow g2"></div>
    <canvas id="particleCanvas"></canvas>

    <div class="ns-hero-inner">
        <div class="ns-hero-badge">
            <i class="fas fa-capsules"></i>
            Droguería &amp; Distribuidora
        </div>

        <h1 class="ns-hero-title">
            Quiénes
            <span class="hl">Somos</span>
        </h1>

        <p class="ns-hero-sub">
            Una empresa chiclayana comprometida con la salud de cada familia peruana.
            Calidad, confianza y compromiso en cada entrega.
        </p>
    </div>

    <div class="ns-hero-scroll">
        <div class="ns-scroll-line"></div>
        Descubre más
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     2. HISTORIA
     ═══════════════════════════════════════════════════ --}}
<section class="ns-section">
    <div class="ns-historia-wrap">
        <div class="sr left">
            <div class="ns-historia-img">
                <img
                    src="{{ ($banner && $banner->historia_image) ? asset('storage/'.$banner->historia_image) : asset('img/hero.png') }}"
                    alt="Nuestra Historia — Droguería Sánchez Pharma"
                    class="ns-historia-img-main"
                    loading="lazy"
                >
                <div class="ns-historia-badge-float">
                    <strong>2022</strong>
                    <span>Año de<br>Fundación</span>
                </div>
            </div>
        </div>

        <div class="sr right">
            <div class="ns-label"><span>Nuestra historia</span></div>
            <h2 class="ns-title">Comprometidos con la <span class="accent">Salud</span> del Perú</h2>
            <div class="ns-divider"></div>
            <p class="ns-body" style="color:var(--slate-600); line-height:1.85; font-size:1.05rem; margin-bottom:12px;">
                {{ $settings['historia'] }}
            </p>
            <p class="ns-body" style="color:var(--slate-600); line-height:1.85; font-size:1.05rem;">
                La empresa destaca por su visión de crecimiento, atención profesional y compromiso con la calidad,
                consolidándose como fuente de empleo y desarrollo en Chiclayo.
            </p>

            <div class="ns-timeline">
                <div class="ns-tl-item">
                    <div class="ns-tl-year">2022</div>
                    <div class="ns-tl-text">Fundación de la empresa en Chiclayo, Lambayeque.</div>
                </div>
                <div class="ns-tl-item">
                    <div class="ns-tl-year">2023</div>
                    <div class="ns-tl-text">Expansión del catálogo a más de 500 productos farmacéuticos.</div>
                </div>
                <div class="ns-tl-item">
                    <div class="ns-tl-year">2024</div>
                    <div class="ns-tl-text">Consolidación de red de ejecutivos de venta a nivel regional.</div>
                </div>
                <div class="ns-tl-item">
                    <div class="ns-tl-year">2025 – Hoy</div>
                    <div class="ns-tl-text">Líderes en distribución farmacéutica con alcance regional y potencial de expansión nacional.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     3. MISIÓN & VISIÓN
     ═══════════════════════════════════════════════════ --}}
<section class="ns-section ns-mv-section">
    <div class="ns-mv-inner">
        <div class="ns-mv-header sr up">
            <div class="ns-label" style="justify-content:center; color:#4ade80;">
                <span style="background:rgba(74,222,128,.5); display:block; width:24px; height:2px; border-radius:2px;"></span>
                <span>¿Por qué elegirnos?</span>
            </div>
            <h2 class="ns-title light-text">Nuestra <span style="color:#4ade80;">Esencia</span></h2>
            <div class="ns-divider center" style="background: linear-gradient(90deg, #4ade80, #22c55e);"></div>
        </div>

        <div class="ns-mv-grid">
            <div class="ns-mv-card sr left">
                <div class="ns-mv-card-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="ns-mv-card-title">Nuestra Misión</div>
                <p class="ns-mv-card-text">{{ $settings['mision'] }}</p>
                <div class="ns-mv-card-bg-num">M</div>
            </div>
            <div class="ns-mv-card sr right">
                <div class="ns-mv-card-icon">
                    <i class="fas fa-binoculars"></i>
                </div>
                <div class="ns-mv-card-title">Nuestra Visión</div>
                <p class="ns-mv-card-text">{{ $settings['vision'] }}</p>
                <div class="ns-mv-card-bg-num">V</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     5.5. CERTIFICACIONES
     ═══════════════════════════════════════════════════ --}}
@if(isset($certificados) && $certificados->count() > 0)
<section class="ns-section" style="background: white; border-top: 1px solid var(--slate-200);">
    <div class="sr up" style="text-align: center; margin-bottom: 60px;">
        <div class="ns-label" style="justify-content: center;"><span>Aval de Calidad</span></div>
        <h2 class="ns-title">Nuestras <span class="accent">Certificaciones</span></h2>
        <div class="ns-divider center"></div>
        <p style="color:var(--slate-600); font-size:1.05rem; line-height:1.75; max-width: 600px; margin: 0 auto;">
            Respaldamos nuestros procesos y productos con certificaciones oficiales de DIGEMID, garantizando la máxima calidad en cada entrega.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto;">
        @foreach($certificados as $index => $cert)
        <div class="sr up" style="transition-delay: {{ $index * 0.1 }}s; background: white; border: 1px solid var(--slate-100); border-radius: 28px; padding: 50px 40px; text-align: center; position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 10px 40px rgba(0,0,0,0.03); display: flex; flex-direction: column; height: 100%;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 25px 60px rgba(34,197,94,0.1)'; this.style.borderColor='rgba(34,197,94,0.3)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.03)'; this.style.borderColor='var(--slate-100)';" >
            
            {{-- Decoración sutil de fondo --}}
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 160px; background: linear-gradient(180deg, rgba(34,197,94,0.04) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>

            <div style="width: 130px; height: 130px; margin: 0 auto 30px; position: relative; z-index: 2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                @if($cert->imagen)
                    <img src="{{ asset('storage/' . $cert->imagen) }}" alt="{{ $cert->nombre }}" style="max-width: 100%; max-height: 100%; object-fit: contain; filter: drop-shadow(0 15px 20px rgba(0,0,0,0.15)); transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='none'">
                @else
                    <div style="width: 100px; height: 100px; background: var(--slate-50); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 4px 10px rgba(0,0,0,0.05);">
                        <i class="fas fa-certificate" style="font-size: 3rem; color: var(--slate-300);"></i>
                    </div>
                @endif
            </div>

            <div style="position: relative; z-index: 2; flex-grow: 1; display: flex; flex-direction: column;">
                <div style="height: 80px; display: flex; flex-direction: column; justify-content: flex-start;">
                    <h4 style="font-family: 'Outfit', sans-serif; margin: 0 0 15px; line-height: 1.3;">
                        @php
                            $name = $cert->nombre;
                            $parts = explode(' DE ', $name, 2);
                        @endphp
                        @if(count($parts) == 2)
                            <span style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--slate-500); letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">{{ $parts[0] }} DE</span>
                            <span style="display: block; font-size: 1.35rem; font-weight: 900; color: var(--slate-800); letter-spacing: -0.5px; text-transform: uppercase;">{{ $parts[1] }}</span>
                        @else
                            <span style="display: block; font-size: 1.25rem; font-weight: 900; color: var(--slate-800); text-transform: uppercase;">{{ $name }}</span>
                        @endif
                    </h4>
                </div>
                
                @if($cert->descripcion)
                <div style="margin-top: 15px;">
                    <span style="display: block; width: 30px; height: 2px; background: var(--green-500); margin: 0 auto 15px; border-radius: 2px;"></span>
                    <p style="color: var(--slate-600); font-size: 0.95rem; line-height: 1.7; margin: 0; position: relative;">
                        {{ $cert->descripcion }}
                    </p>
                </div>
                @endif

                <div style="flex: 1;"></div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════
     5. PRINCIPIOS
     ═══════════════════════════════════════════════════ --}}
<section class="ns-section ns-principios-section">
    <div class="ns-principios-wrap">
        {{-- Accordion --}}
        <div>
            <div class="sr left" style="margin-bottom:40px;">
                <div class="ns-label"><span>Lo que nos guía</span></div>
                <h2 class="ns-title">Principios <span class="accent">Institucionales</span></h2>
                <div class="ns-divider"></div>
                <p style="color:var(--slate-600); font-size:1rem; line-height:1.75; margin-bottom:32px;">
                    Guiamos cada acción y decisión mediante principios sólidos que garantizan integridad y compromiso con nuestros clientes y la sociedad.
                </p>
            </div>

            <div class="ns-accordion sr left" id="principiosAccordion">
                @php 
                    $i = 1; 
                    // Limpieza exhaustiva de la cadena, reemplazando retornos de carro, "n/", etc.
                    $principiosRaw = str_replace(['n/', 'n /', '\\n', '\n', '\r'], "\n", $settings['principios']);
                    $principiosArr = array_filter(explode("\n", $principiosRaw));
                @endphp
                @foreach($principiosArr as $principio)
                @if(trim($principio))
                @php $cleanP = trim($principio, " \t\n\r\0\x0B•-"); @endphp
                <div class="ns-acc-item {{ $i === 1 ? 'open' : '' }}" data-acc>
                    <div class="ns-acc-header" role="button" tabindex="0" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}">
                        <div class="ns-acc-num">{{ sprintf('%02d', $i) }}</div>
                        <div class="ns-acc-title">{{ $cleanP }}</div>
                        <i class="fas fa-chevron-down ns-acc-chevron"></i>
                    </div>
                    <div class="ns-acc-body" style="{{ $i === 1 ? 'max-height:200px;' : '' }}">
                        <div class="ns-acc-body-inner">
                            Un compromiso real con la excelencia: <em>{{ strtolower($cleanP) }}</em> es parte de nuestro ADN organizacional.
                        </div>
                    </div>
                </div>
                @php $i++; @endphp
                @endif
                @endforeach
            </div>
        </div>

        {{-- Visual Card --}}
        <div class="sr right">
            <div class="ns-pv-card">
                <div class="ns-pv-content">
                    <div class="ns-pv-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="ns-pv-title">Comprometidos con la Excelencia</div>
                    <p class="ns-pv-body">
                        Cada principio que seguimos no es simplemente una declaración — es una promesa que renovamos cada día
                        con nuestros clientes, colaboradores y comunidad.
                    </p>
                    <div class="ns-pv-metrics">
                        <div class="ns-pv-metric">
                            <strong>100%</strong>
                            <span>Productos certificados</span>
                        </div>
                        <div class="ns-pv-metric">
                            <strong>24/7</strong>
                            <span>Soporte al cliente</span>
                        </div>
                        <div class="ns-pv-metric">
                            <strong>+500</strong>
                            <span>Productos disponibles</span>
                        </div>
                        <div class="ns-pv-metric">
                            <strong>3+</strong>
                            <span>Años de trayectoria</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     4. VALORES
     ═══════════════════════════════════════════════════ --}}
<section class="ns-section ns-valores-section">
    <div class="ns-valores-header sr up">
        <div class="ns-label" style="justify-content:center;"><span>Nuestros Pilares</span></div>
        <h2 class="ns-title">Valores <span class="accent">Corporativos</span></h2>
        <div class="ns-divider center"></div>
        <p style="color:var(--slate-500); max-width:520px; margin:0 auto; font-size:1rem; line-height:1.7;">
            Cada decisión y cada entrega está guiada por estos principios que nos definen.
        </p>
    </div>

    <div class="ns-valores-grid">
        @php
        $valDescriptions = [
            'compromiso'  => 'Cumplimos lo que prometemos. Tu confianza es nuestra prioridad en cada entrega.',
            'honestidad'  => 'Actuamos con transparencia y rectitud en todas nuestras relaciones comerciales.',
            'innovaci'    => 'Buscamos constantemente mejorar nuestros procesos y soluciones para el mercado.',
            'servicio'    => 'El cliente es el centro de todo lo que hacemos. Atención personalizada y oportuna.',
            'calidad'     => 'Solo distribuimos productos que cumplen los más altos estándares farmacéuticos.',
        ];
        $valIcons = [
            'compromiso' => 'hand-holding-heart',
            'honestidad' => 'shield-halved',
            'innovaci'   => 'lightbulb',
            'servicio'   => 'headset',
            'calidad'    => 'award',
            'ética'      => 'scale-balanced',
            'integridad' => 'user-shield',
        ];
        @endphp

        @foreach(explode(',', $settings['valores']) as $index => $valor)
        @php
            $valClean = trim($valor);
            $valLower = strtolower($valClean);
            $icon = 'star';
            $desc = 'Guiando cada acción con ' . strtolower($valClean) . ' y excelencia.';
            foreach ($valIcons as $key => $ico) {
                if (str_contains($valLower, $key)) { $icon = $ico; break; }
            }
            foreach ($valDescriptions as $key => $d) {
                if (str_contains($valLower, $key)) { $desc = $d; break; }
            }
        @endphp
        <div class="ns-flip-card sr scale" style="transition-delay: {{ $index * 0.08 }}s">
            <div class="ns-flip-inner">
                {{-- Front --}}
                <div class="ns-flip-front">
                    <div class="ns-flip-icon">
                        <i class="fas fa-{{ $icon }}"></i>
                    </div>
                    <div class="ns-flip-label">{{ $valClean }}</div>
                </div>
                {{-- Back --}}
                <div class="ns-flip-back">
                    <div class="ns-flip-back-icon">
                        <i class="fas fa-{{ $icon }}"></i>
                    </div>
                    <div class="ns-flip-back-title">{{ $valClean }}</div>
                    <div class="ns-flip-back-text">{{ $desc }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     6. CTA
     ═══════════════════════════════════════════════════ --}}
<section class="ns-cta">
    <div class="ns-cta-ring"></div>
    <div class="ns-cta-ring"></div>
    <div class="ns-cta-ring"></div>

    <div class="ns-cta-inner sr up">
        <h2 class="ns-cta-title">
            ¿Listo para trabajar<br>
            <span class="hl">con nosotros?</span>
        </h2>
        <p class="ns-cta-sub">
            Contáctanos hoy mismo y descubre cómo podemos ayudarte a acceder a medicamentos de calidad con la mejor atención.
        </p>
        <div class="ns-cta-actions">
            <a href="{{ route('contact') }}" class="ns-btn ns-btn-primary">
                <i class="fas fa-envelope"></i>
                Contáctanos ahora
            </a>
            <a href="{{ route('products') }}" class="ns-btn ns-btn-outline">
                <i class="fas fa-pills"></i>
                Ver catálogo
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
/* ─── Scroll Reveal ─── */
const srObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const delay = parseFloat(e.target.style.transitionDelay || 0);
            setTimeout(() => e.target.classList.add('in'), delay * 1000);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
document.querySelectorAll('.sr').forEach(el => srObserver.observe(el));

/* ─── Counter Animation ─── */
function animateCounter(el) {
    const target = parseInt(el.dataset.target);
    const suffix = el.dataset.suffix || '';
    if (!target) return;
    const duration = 1800;
    const start = performance.now();
    function step(ts) {
        const elapsed = ts - start;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 4);
        el.textContent = Math.round(target * ease) + suffix;
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting && e.target.dataset.target) {
            animateCounter(e.target);
            counterObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('[data-target]').forEach(el => counterObserver.observe(el));

/* ─── Hero BG parallax load ─── */
setTimeout(() => document.getElementById('heroBg')?.classList.add('loaded'), 100);

/* ─── Particle Canvas ─── */
(function(){
    const canvas = document.getElementById('particleCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];
    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x = Math.random() * W;
            this.y = Math.random() * H;
            this.r = Math.random() * 1.8 + .4;
            this.vx = (Math.random() - .5) * .4;
            this.vy = -Math.random() * .6 - .2;
            this.alpha = 0;
            this.targetAlpha = Math.random() * .5 + .1;
            this.life = 0;
            this.maxLife = Math.random() * 200 + 150;
        }
        update() {
            this.life++;
            if (this.life < 30) this.alpha = this.targetAlpha * (this.life / 30);
            else if (this.life > this.maxLife - 30) this.alpha = this.targetAlpha * ((this.maxLife - this.life) / 30);
            else this.alpha = this.targetAlpha;
            this.x += this.vx;
            this.y += this.vy;
            if (this.life >= this.maxLife) this.reset();
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = '#4ade80';
            ctx.fill();
            ctx.restore();
        }
    }
    for (let i = 0; i < 80; i++) {
        const p = new Particle();
        p.life = Math.random() * p.maxLife; // stagger
        particles.push(p);
    }
    function loop() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(loop);
    }
    loop();
})();

/* ─── Accordion ─── */
document.querySelectorAll('[data-acc]').forEach(item => {
    const header = item.querySelector('.ns-acc-header');
    const body   = item.querySelector('.ns-acc-body');
    header.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        // close all
        document.querySelectorAll('[data-acc]').forEach(i => {
            i.classList.remove('open');
            i.querySelector('.ns-acc-body').style.maxHeight = '0';
            i.querySelector('.ns-acc-header').setAttribute('aria-expanded', 'false');
        });
        // open clicked
        if (!isOpen) {
            item.classList.add('open');
            body.style.maxHeight = body.scrollHeight + 'px';
            header.setAttribute('aria-expanded', 'true');
        }
    });
    header.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); header.click(); } });
});
</script>
@endpush