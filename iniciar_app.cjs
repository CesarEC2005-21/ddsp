const os = require('os');
const fs = require('fs');
const { execSync } = require('child_process');
const path = require('path');

// 1. Obtener la IP local de la computadora (WiFi/Ethernet)
function getLocalIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            // Ignorar localhost y buscar IPv4
            if (iface.family === 'IPv4' && !iface.internal) {
                // Priorizar explícitamente Wi-Fi primero para evitar VirtualBox
                if (name.toLowerCase().includes('wi-fi') || name.toLowerCase().includes('wireless')) {
                    return iface.address;
                }
            }
        }
    }
    // Fallback 2: Buscar ethernet que no sea de VirtualBox (192.168.56.x)
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('192.168.56.')) {
                return iface.address;
            }
        }
    }
    return '127.0.0.1';
}

const ip = getLocalIP();
const url = `http://${ip}:8000`;
console.log(`========================================`);
console.log(`🌐 IP Detectada automáticamente: ${ip}`);
console.log(`🔗 Configurando URL a: ${url}`);
console.log(`========================================`);

// 2. Actualizar el archivo .env
const envPath = path.join(__dirname, '.env');
if (fs.existsSync(envPath)) {
    let envContent = fs.readFileSync(envPath, 'utf8');
    envContent = envContent.replace(/APP_URL=http[s]?:\/\/[^\s]+/, `APP_URL=${url}`);
    fs.writeFileSync(envPath, envContent);
    console.log(`✅ Archivo .env actualizado.`);
}

// 3. Actualizar capacitor.config.ts
const capPath = path.join(__dirname, 'capacitor.config.ts');
if (fs.existsSync(capPath)) {
    let capContent = fs.readFileSync(capPath, 'utf8');
    // Reemplazar cualquier URL de tipo http/https en la sección "url:"
    capContent = capContent.replace(/url:\s*'http[s]?:\/\/[^']+'/, `url: '${url}'`);
    fs.writeFileSync(capPath, capContent);
    console.log(`✅ Archivo capacitor.config.ts actualizado.`);
}

// 4. Sincronizar Capacitor
console.log(`\n🔄 Sincronizando Capacitor con Android... (espera unos segundos)`);
try {
    execSync('npx cap sync android', { stdio: 'inherit' });
    console.log(`✅ Sincronización exitosa.`);
} catch (error) {
    console.error(`❌ Error al sincronizar:`, error.message);
}

// 5. Iniciar Laravel
console.log(`\n🚀 Iniciando servidor Laravel...`);
console.log(`⚠️ ATENCIÓN: ¡Asegúrate de RECOMPILAR (Play/Run) la app en Android Studio para tomar los cambios!\n`);

try {
    // Esto se quedará corriendo hasta que el usuario presione Ctrl+C
    execSync('php artisan serve --host=0.0.0.0 --port=8000', { stdio: 'inherit' });
} catch (error) {
    // Si el usuario cancela con Ctrl+C, saldrá limpio
}
