const fs = require('fs');
const file = 'd:/xampp/htdocs/ddsp/resources/views/landing/nosotros.blade.php';
let content = fs.readFileSync(file, 'utf8');

// The markers for the sections
const valoresStart = "{{-- ═══════════════════════════════════════════════════\n     4. VALORES";
const principiosStart = "{{-- ═══════════════════════════════════════════════════\n     5. PRINCIPIOS";
const certStart = "{{-- ═══════════════════════════════════════════════════\n     5.5. CERTIFICACIONES";
const ctaStart = "{{-- ═══════════════════════════════════════════════════\n     6. CTA";

const idxValores = content.indexOf(valoresStart);
const idxPrincipios = content.indexOf(principiosStart);
const idxCert = content.indexOf(certStart);
const idxCta = content.indexOf(ctaStart);

const beforeValores = content.substring(0, idxValores);
const blockValores = content.substring(idxValores, idxPrincipios);
const blockPrincipios = content.substring(idxPrincipios, idxCert);
const blockCert = content.substring(idxCert, idxCta);
const afterCta = content.substring(idxCta);

// New order: Cert -> Principios -> Valores
const newContent = beforeValores + blockCert + blockPrincipios + blockValores + afterCta;
fs.writeFileSync(file, newContent, 'utf8');
console.log("Reordered successfully");
