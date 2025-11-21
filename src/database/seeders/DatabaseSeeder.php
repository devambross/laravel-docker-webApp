<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Evento;
use App\Models\Mesa;
use App\Models\ParticipanteEvento;
use App\Models\EntradaClub;
use App\Models\EntradaEvento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar todas las tablas de eventos
        // Para SQLite, necesitamos deshabilitar las restricciones de clave foránea de forma diferente
        DB::statement('PRAGMA foreign_keys = OFF;');

        EntradaEvento::truncate();
        EntradaClub::truncate();
        ParticipanteEvento::truncate();
        Mesa::truncate();
        Evento::truncate();

        DB::statement('PRAGMA foreign_keys = ON;');

        echo "✓ Base de datos limpiada. Todas las tablas de eventos vaciadas.\n";
        echo "✓ API de socios funcionando con datos simulados (SocioAPISimulada.php)\n";
        echo "✓ Sistema listo para probar registro de eventos, mesas y asignaciones.\n";
    }
}
