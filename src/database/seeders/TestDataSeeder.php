<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Seed the application's database with test data.
     */
    public function run(): void
    {
        // Limpiar datos existentes
        DB::table('entrada_evento')->delete();
        DB::table('participantes_evento')->delete();
        DB::table('entrada_club')->delete();
        DB::table('mesas')->delete();
        DB::table('eventos')->delete();

        // Crear eventos de prueba
        $eventos = [
            [
                'nombre' => 'Cena Anual 2025',
                'fecha' => Carbon::create(2025, 12, 31),
                'area' => 'Salón Principal',
                'capacidad_total' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Fiesta de Fin de Año',
                'fecha' => Carbon::create(2025, 12, 25),
                'area' => 'Terraza',
                'capacidad_total' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Torneo de Tenis',
                'fecha' => Carbon::today()->addDays(7),
                'area' => 'Canchas',
                'capacidad_total' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($eventos as $evento) {
            DB::table('eventos')->insert($evento);
        }

        // Crear mesas para el primer evento (Cena Anual)
        $mesas = [
            ['evento_id' => 1, 'numero_mesa' => '1', 'capacidad' => 8],
            ['evento_id' => 1, 'numero_mesa' => '2', 'capacidad' => 8],
            ['evento_id' => 1, 'numero_mesa' => '3', 'capacidad' => 6],
            ['evento_id' => 1, 'numero_mesa' => '4', 'capacidad' => 10],
            ['evento_id' => 1, 'numero_mesa' => '5', 'capacidad' => 8],
        ];

        $capacidadTotal = 0;
        foreach ($mesas as $mesa) {
            DB::table('mesas')->insert([
                'evento_id' => $mesa['evento_id'],
                'numero_mesa' => $mesa['numero_mesa'],
                'capacidad' => $mesa['capacidad'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $capacidadTotal += $mesa['capacidad'];
        }

        // Actualizar capacidad total del evento
        DB::table('eventos')->where('id', 1)->update(['capacidad_total' => $capacidadTotal]);

        // Crear participantes de prueba
        $participantes = [
            // Socios
            [
                'evento_id' => 1,
                'mesa_id' => 1,
                'numero_silla' => 1,
                'tipo' => 'socio',
                'codigo_socio' => '0001',
                'codigo_participante' => '0001',
                'dni' => '12345678',
                'nombre' => 'Juan Pérez',
                'n_recibo' => 'REC-001',
                'n_boleta' => 'BOL-001'
            ],
            // Familiar
            [
                'evento_id' => 1,
                'mesa_id' => 1,
                'numero_silla' => 2,
                'tipo' => 'socio',
                'codigo_socio' => '0001-A',
                'codigo_participante' => '0001-A',
                'dni' => '87654321',
                'nombre' => 'María Pérez',
                'n_recibo' => 'REC-002',
                'n_boleta' => 'BOL-002'
            ],
            // Invitado de evento
            [
                'evento_id' => 1,
                'mesa_id' => 1,
                'numero_silla' => 3,
                'tipo' => 'invitado',
                'codigo_socio' => '0001',
                'codigo_participante' => '0001-INV1',
                'dni' => '11111111',
                'nombre' => 'Pedro García',
                'n_recibo' => null,
                'n_boleta' => 'BOL-003'
            ],
            [
                'evento_id' => 1,
                'mesa_id' => 1,
                'numero_silla' => 4,
                'tipo' => 'socio',
                'codigo_socio' => '0234',
                'codigo_participante' => '0234',
                'dni' => '22222222',
                'nombre' => 'Ana López',
                'n_recibo' => 'REC-003',
                'n_boleta' => 'BOL-004'
            ],
            [
                'evento_id' => 1,
                'mesa_id' => 2,
                'numero_silla' => 1,
                'tipo' => 'socio',
                'codigo_socio' => '0456',
                'codigo_participante' => '0456',
                'dni' => '33333333',
                'nombre' => 'Carlos Rodríguez',
                'n_recibo' => 'REC-004',
                'n_boleta' => 'BOL-005'
            ]
        ];

        foreach ($participantes as $participante) {
            $id = DB::table('participantes_evento')->insertGetId(array_merge($participante, [
                'created_at' => now(),
                'updated_at' => now()
            ]));

            // Crear registro de entrada_evento
            DB::table('entrada_evento')->insert([
                'participante_evento_id' => $id,
                'entrada_club' => false,
                'entrada_evento' => false,
                'fecha_hora_club' => null,
                'fecha_hora_evento' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear registros de entrada al club (historial)
        $entradasClub = [
            [
                'codigo_participante' => '0001',
                'tipo' => 'socio',
                'nombre' => 'Juan Pérez',
                'dni' => '12345678',
                'area' => 'Piscina',
                'fecha_hora' => Carbon::today()->subDays(2)->setTime(10, 30)
            ],
            [
                'codigo_participante' => '0001-A',
                'tipo' => 'socio',
                'nombre' => 'María Pérez',
                'dni' => '87654321',
                'area' => 'Gimnasio',
                'fecha_hora' => Carbon::today()->subDays(1)->setTime(14, 15)
            ],
            [
                'codigo_participante' => '0500',
                'tipo' => 'invitado',
                'nombre' => 'Invitado Temporal 1',
                'dni' => '99999999',
                'area' => 'Cancha de Tenis',
                'fecha_hora' => Carbon::today()->subDays(3)->setTime(16, 0)
            ],
            [
                'codigo_participante' => '0789',
                'tipo' => 'invitado',
                'nombre' => 'Invitado Temporal 2',
                'dni' => '88888888',
                'area' => 'Restaurante',
                'fecha_hora' => Carbon::yesterday()->setTime(12, 30)
            ]
        ];

        foreach ($entradasClub as $entrada) {
            DB::table('entrada_club')->insert(array_merge($entrada, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('✅ Datos de prueba creados exitosamente:');
        $this->command->info('   - 3 eventos');
        $this->command->info('   - 5 mesas (Evento 1)');
        $this->command->info('   - 5 participantes en eventos');
        $this->command->info('   - 4 entradas al club (historial)');
    }
}
