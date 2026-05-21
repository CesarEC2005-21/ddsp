<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$labs = [
    "MSD", "MSN LABORATORIO", "MEDICAL STORE", "NATTURA LABS", "NATURAL HEALTH", "NESTLE", 
    "NEXCARE", "NINET", "NIPRO", "NORDIC PHARMACEUTICAL", "NOSOTRAS", "NUTRALAB PHARMACEUTICAL", 
    "OM PHARMA", "OQ PHARMACEUTICAL", "ORGANON", "P&G", "PERFAR", "PERULAB", "PERUFARMA", "PFIZER", 
    "PHARMED", "PHARMAGENERICOS", "PHARMAGEN", "KIMBERLY CLARK PERU", "PLUS COSMETICA", "PROBIOTICAL", 
    "PROCAPS", "PROTISA PERU", "PORTUGAL", "PRUDENTIAL", "PROTEX", "PUIG PERU", "QM PHARMA", "QUALA", 
    "QUIBRIM SAC", "QUILAB", "QUIMEDIC PLUS", "QUALIMAX", "RBHCR", "REYES", "ROCCIA", "ROSTER", "ROWE", 
    "ROXFARMA", "SAMPLIX", "SANIMED", "SANOFI - AVENTIS", "SCHWARZKOPF", "SANDAVA PHARMA", "SEBAL FARMA", 
    "SEVEN PHARMA", "SHERFARMA", "SIEGFRIED", "SMART PHARMA", "SOFTY", "STARBRANDS", "STERILAB", "SUNLIFE", 
    "SAVAL", "TECNOFARMA", "TEVA", "THEFAR", "MEDIHEALTH", "TUINIES", "UNILEVER", "UNIMED", "VIDASOL", 
    "VITALINE", "VITALIS", "WAR INVERSIONES S.A.C", "WHH PHARMA", "X-MEDIC", "ZAMBON"
];

foreach ($labs as $lab) {
    if (!\App\Models\Laboratory::where('descripcion', $lab)->exists()) {
        $lastId = \App\Models\Laboratory::max('id') ?? 0;
        \App\Models\Laboratory::create([
            'codigo' => 'LAB-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT),
            'descripcion' => $lab,
            'is_top' => false,
            'estado' => true,
        ]);
    }
}
echo "Laboratories imported!\n";
