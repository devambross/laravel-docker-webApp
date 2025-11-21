<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Evento;
use App\Models\Mesa;
use App\Models\ParticipanteEvento;
use App\Models\EntradaClub;
use App\Models\EntradaEvento;
use App\Services\SocioAPISimulada;

echo "\n=== ESTADO DE LA BASE DE DATOS ===\n\n";

echo "Eventos: " . Evento::count() . "\n";
echo "Mesas: " . Mesa::count() . "\n";
echo "Participantes: " . ParticipanteEvento::count() . "\n";
echo "Entradas Club: " . EntradaClub::count() . "\n";
echo "Entradas Evento: " . EntradaEvento::count() . "\n";

echo "\n=== SOCIOS DISPONIBLES EN API SIMULADA ===\n\n";

$socios = SocioAPISimulada::getAllSocios();
foreach ($socios as $codigo => $socio) {
    $totalFamiliares = count($socio['familiares']);
    echo "• {$socio['codigo']} - {$socio['nombre']} ({$totalFamiliares} familiares)\n";
    foreach ($socio['familiares'] as $familiar) {
        echo "  └─ {$familiar['codigo']} - {$familiar['nombre']} ({$familiar['parentesco']})\n";
    }
}

echo "\n✓ Sistema listo para pruebas\n\n";
