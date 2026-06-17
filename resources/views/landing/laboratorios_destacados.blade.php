<style>
    .lab-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 40px 20px;
        background: #f8fafc; /* Color de fondo sutil parecido a la imagen */
    }

    .lab-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        width: 320px;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .lab-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .lab-card-top {
        height: 200px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Patrón de puntos superior izquierdo para dar textura */
    .lab-card-top::after {
        content: '';
        position: absolute;
        top: 10px;
        left: 10px;
        width: 60px;
        height: 60px;
        background-image: radial-gradient(rgba(0,0,0,0.05) 2px, transparent 2px);
        background-size: 8px 8px;
        z-index: 0;
        border-radius: 50%;
    }

    .lab-logo-wrapper {
        position: relative;
        z-index: 1;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }

    .lab-logo-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .wave-container {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background-size: cover;
        background-position: bottom;
    }

    .lab-card-bottom {
        padding: 20px;
        text-align: center;
        background: #ffffff;
        z-index: 2;
        border-top: 1px solid rgba(0,0,0,0.02);
    }

    .lab-card-bottom h4 {
        margin: 0;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        font-weight: 800;
        color: #1a202c;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    /* --- GENFAR --- */
    .bg-genfar {
        background: linear-gradient(135deg, #ffffff 0%, rgba(218, 41, 28, 0.05) 100%);
    }
    .bg-genfar .wave-container {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23da291c' fill-opacity='1' d='M0,160L80,176C160,192,320,224,480,218.7C640,213,800,171,960,160C1120,149,1280,171,1360,181.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3Cpath fill='%23b71c1c' fill-opacity='0.7' d='M0,224L80,213.3C160,203,320,181,480,186.7C640,192,800,224,960,218.7C1120,213,1280,171,1360,149.3L1440,128L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
    }

    /* --- BAYER --- */
    .bg-bayer {
        background: linear-gradient(135deg, #ffffff 0%, rgba(0, 158, 227, 0.05) 100%);
    }
    .bg-bayer .wave-container {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23009EE3' fill-opacity='1' d='M0,160L80,176C160,192,320,224,480,218.7C640,213,800,171,960,160C1120,149,1280,171,1360,181.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3Cpath fill='%2365B32E' fill-opacity='0.8' d='M0,224L80,213.3C160,203,320,181,480,186.7C640,192,800,224,960,218.7C1120,213,1280,171,1360,149.3L1440,128L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
    }

    /* --- PORTUGAL --- */
    .bg-portugal {
        background: linear-gradient(135deg, #ffffff 0%, rgba(0, 51, 160, 0.05) 100%);
    }
    .bg-portugal .wave-container {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%230055ff' fill-opacity='0.9' d='M0,160L80,176C160,192,320,224,480,218.7C640,213,800,171,960,160C1120,149,1280,171,1360,181.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3Cpath fill='%230033A0' fill-opacity='0.9' d='M0,224L80,213.3C160,203,320,181,480,186.7C640,192,800,224,960,218.7C1120,213,1280,171,1360,149.3L1440,128L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
    }
</style>

<div class="lab-cards-container">
    <!-- Tarjeta GENFAR -->
    <div class="lab-card">
        <div class="lab-card-top bg-genfar">
            <div class="wave-container"></div>
            <div class="lab-logo-wrapper">
                <!-- Reemplazar src con la ruta real del logo de Genfar -->
                <img src="https://via.placeholder.com/150x80/DA291C/FFFFFF?text=Genfar" alt="Logo Genfar">
            </div>
        </div>
        <div class="lab-card-bottom">
            <h4>GENFAR</h4>
        </div>
    </div>

    <!-- Tarjeta BAYER -->
    <div class="lab-card">
        <div class="lab-card-top bg-bayer">
            <div class="wave-container"></div>
            <div class="lab-logo-wrapper">
                <!-- Reemplazar src con la ruta real del logo de Bayer -->
                <img src="https://via.placeholder.com/120x120/009EE3/FFFFFF?text=Bayer" alt="Logo Bayer" style="border-radius: 50%;">
            </div>
        </div>
        <div class="lab-card-bottom">
            <h4>BAYER</h4>
        </div>
    </div>

    <!-- Tarjeta PORTUGAL -->
    <div class="lab-card">
        <div class="lab-card-top bg-portugal">
            <div class="wave-container"></div>
            <div class="lab-logo-wrapper">
                <!-- Reemplazar src con la ruta real del logo de Portugal -->
                <img src="https://via.placeholder.com/120x120/0033A0/FFFFFF?text=Portugal" alt="Logo Portugal" style="border-radius: 50%;">
            </div>
        </div>
        <div class="lab-card-bottom">
            <h4>PORTUGAL</h4>
        </div>
    </div>
</div>
