import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.sanchezpharma.app',
  appName: 'Sanchez Pharma',
  webDir: 'public',
  server: {
    // ─────────────────────────────────────────────────────────────
    //  CAMBIA ESTA URL PARA PRODUCCIÓN:
    //  1) Sube la web Laravel a tu hosting con HTTPS.
    //  2) Reemplaza la URL de abajo por tu dominio real, por ejemplo:
    //       url: 'http://192.168.100.25:8000'
    //  3) Quita `cleartext: true` cuando uses https (no hace falta).
    //  4) Vuelve a compilar el APK (npx cap sync android y gradlew).
    // ─────────────────────────────────────────────────────────────
    //  LOCAL: apunta al servidor Laravel (`php artisan serve --host=0.0.0.0`).
    url: 'http://192.168.100.25:8000',
    cleartext: true,
  },
  android: {
    allowMixedContent: true,
  },
  plugins: {
    StatusBar: {
      style: 'DARK',
      backgroundColor: '#1B5E20',
    }
  }
};

export default config;